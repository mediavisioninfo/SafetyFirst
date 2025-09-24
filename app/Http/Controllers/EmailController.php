<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Claim;
use App\Models\InsuranceDetail;
use App\Models\EmailTemplate;
use App\Mail\ClaimEmailMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\ClaimController;

class EmailController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('emails.index', compact('templates'));
    }

    public function getTemplate($id, $claimId)
    {
        $template = EmailTemplate::findOrFail($id);
        $claim = Claim::findOrFail($claimId);
         $insuranceDetail = InsuranceDetail::select('policy_number', 'insured_name', 'vehicle')
            ->where('claim_id', $claimId)
            ->first();

        // dd($insuranceDetail);
        $replacements = [
            '{{ workshop_name }}' => $claim->workshop_name,
            '{{ claim_id }}' => $claim->claim_id,
            '{{ insured_name }}' => $insuranceDetail->insured_name,
            '{{ policy_number }}' => $insuranceDetail->policy_number,
            '{{ vehicle_number }}' => $insuranceDetail->vehicle,
            '{{ approved_date }}' => $claim->date,
            '{{ loss_date }}' => $claim->loss_date,
            '{{ contact_details }}' => '7880112303',
        ];

        $body = strtr($template->body, $replacements);
        $subject = strtr($template->subject, $replacements);

        return response()->json([
            'subject' => $subject,
            'body' => $body,
        ]);
    }

    /*public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        Email::create([
            'template_id' => $request->template_id,
            'claim_id' => $request->claim_id,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return response()->json(['success' => true, 'message' => 'Email saved successfully!']);
    }*/

    /*public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
            'subject' => 'required|string',
            'body' => 'required|string',
            'claim_id' => 'required|exists:claims,id',
        ]);

        // Save to DB
        $email = Email::create([
            'template_id' => $request->template_id,
            'claim_id' => $request->claim_id,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        // Get claim and workshop email
        $claim = Claim::find($request->claim_id);

        if ($claim->workshop_email) {
            // Send email
            Mail::to($claim->workshop_email)->send(new ClaimEmailMailable(
                $request->subject,
                $request->body
            ));
        }

        return response()->json(['success' => true, 'message' => 'Email saved and sent successfully!']);
    }*/

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
            'subject' => 'required|string',
            'body' => 'required|string',
            'claim_id' => 'required|exists:claims,id',
            'cc' => 'nullable|string'
        ]);

        // Get claim and workshop email
        $claim = Claim::find($request->claim_id);

        // Get email(s) from workshop
        $recipients = $claim->workshop_email;

        // Convert to array if multiple emails are comma separated
        $recipientArray = array_map('trim', explode(',', $recipients));

        // Handle CC emails
        $ccArray = [];
        if (!empty($request->cc)) {
            $ccArray = array_map('trim', explode(',', $request->cc));
        }

        // dd($request->claim_id);
        // Save to DB with default status
        $email = Email::create([
            'template_id' => $request->template_id,
            'claim_id' => $request->claim_id,
            'recipients' => implode(',', $recipientArray), 
            'cc'  => implode(',', $ccArray),
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => 'Pending',
        ]);

        // Try sending emails
        if (!empty($recipientArray)) {
            foreach ($recipientArray as $recipient) {
                $mail = Mail::to($recipient);
                if (!empty($ccArray)) {
                    $mail->cc($ccArray);
                }
                $mail->send(new ClaimEmailMailable($request->subject, $request->body));
            }
            $email->update(['status' => 'Delivered']);
        } else {
            $email->update(['status' => 'Failed']);
            return response()->json([
                'success' => true,
                'message' => 'Email Failed!',
                'status'  => $email->status
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email processed successfully!',
            'status'  => $email->status
        ]);

    }



    /*public function uploadDocument(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $extension = $file->getClientOriginalExtension();
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

            if (!in_array(strtolower($extension), $allowed)) {
                return response()->json(['error' => 'Invalid file type'], 400);
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/emails'), $filename);
            $url = asset('uploads/emails/' . $filename);

            if (strtolower($extension) === 'pdf') {
                return response()->json([
                    'url' => $url,
                    'default' => '<a href="' . $url . '" target="_blank">Download PDF</a>'
                ]);
            }

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }*/


    public function uploadDocument(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $extension = $file->getClientOriginalExtension();
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

            if (!in_array($extension, $allowed)) {
                return response()->json(['error' => 'Invalid file type'], 400);
            }

            // Claim & folder details
            $claimId = $request->input('claim_id'); // Pass from frontend
            $documentType = 'send_mail'; // Pass from frontend

            $claimHash  = md5($claimId);
            $folderCode = getFolderCode($documentType); // using your global function
            $dir = config('constant.claim_upload_path') . "/{$claimHash}/{$folderCode}";

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            // Secure random filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileNameToStore = md5($originalName . time() . rand()) . '.' . $extension;
            $filePath = $dir . '/' . $fileNameToStore;

            // Encrypt before saving
            $encryptedContent = Crypt::encrypt(file_get_contents($file));
            file_put_contents($filePath, $encryptedContent);

            // Create URL using showImage()
            $url = route('secure.image', [
                'claimHash'  => $claimHash,
                'folderHash' => $folderCode,   // first folder
                'folderCode' => 'null',        // second folder
                'filename'   => $fileNameToStore
            ]);

            if ($extension === 'pdf') {
                $previewHtml = '
                    <div style="display:inline-block; border:1px solid #ccc; border-radius:8px; padding:10px; width:200px; text-align:center; cursor:pointer;" onclick="window.open(\'' . $url . '\', \'_blank\')">
                        <img src="/images/pdf-icon.png" alt="PDF" style="width:80px; margin-bottom:8px;">
                        <div style="font-size:14px; color:#333; font-weight:bold;">PDF Document</div>
                        <div style="font-size:12px; color:#666;">Click to view</div>
                    </div>
                ';

                return response()->json([
                    'url'     => $url,
                    'default' => $previewHtml
                ]);
            }

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }




}
