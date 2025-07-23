<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage document type')) {
            $documentTypes = DocumentType::where('parent_id', parentId())->get();
            return view('document_type.index', compact('documentTypes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('document_type.create');
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create document type') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $documentType = new DocumentType();
            $documentType->title = $request->title;
            $documentType->parent_id = parentId();
            $documentType->save();

            return redirect()->back()->with('success', __('Document type successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(DocumentType $documentType)
    {
        //
    }


    public function edit(DocumentType $documentType)
    {
        return view('document_type.edit',compact('documentType'));
    }


    public function update(Request $request, DocumentType $documentType)
    {
        if (\Auth::user()->can('edit document type') ) {
            $validator = \Validator::make(
                $request->all(), [
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $documentType->title = $request->title;
            $documentType->save();

            return redirect()->back()->with('success', __('Document type successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(DocumentType $documentType)
    {
        if (\Auth::user()->can('delete document type') ) {
            $documentType->delete();
            return redirect()->back()->with('success', 'Document type successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
