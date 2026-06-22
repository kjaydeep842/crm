<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Lead;
use App\Models\Activity;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $query = Document::with('lead');
        
        $user = Auth::user();
        if ($user && $user->role !== 'admin') {
            $query->whereHas('lead', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
        }

        $documents = $query->latest()->paginate(15);
        $leads = Lead::all();

        return view('documents.index', compact('documents', 'leads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'type' => 'required|string', // Proposal, Quotation, Invoice
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        
        // Call AI Service to generate document content
        $proposalData = $this->aiService->generateProposal(
            $lead->company_name ?: $lead->full_name,
            $lead->requirement,
            '₹' . number_format($request->amount)
        );

        $prefix = match ($request->type) {
            'Proposal' => 'PROP',
            'Quotation' => 'QUOT',
            'Invoice' => 'INV',
            default => 'DOC'
        };

        $documentNumber = $prefix . '-' . date('Ymd') . '-' . rand(100, 999);

        $document = Document::create([
            'lead_id' => $lead->id,
            'type' => $request->type,
            'document_number' => $documentNumber,
            'title' => $request->title,
            'amount' => $request->amount,
            'content' => $proposalData
        ]);

        Activity::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'type' => 'Status Change',
            'description' => "Generated AI {$request->type} (#{$documentNumber}) worth ₹" . number_format($request->amount) . "."
        ]);

        return redirect()->route('documents.show', $document->id)->with('success', 'AI Document generated successfully.');
    }

    public function show($id)
    {
        $document = Document::with('lead')->findOrFail($id);
        return view('documents.show', compact('document'));
    }
}
