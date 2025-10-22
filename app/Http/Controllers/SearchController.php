<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consumer;
use App\Models\Meter;
use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Subdivision;

class SearchController extends Controller
{
    /**
     * Global search across all modules.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->back();
        }

        $results = [
            'consumers' => $this->searchConsumers($query),
            'meters' => $this->searchMeters($query),
            'bills' => $this->searchBills($query),
            'complaints' => $this->searchComplaints($query),
            'subdivisions' => $this->searchSubdivisions($query),
        ];

        return view('admin.search.results', compact('results', 'query'));
    }

    /**
     * Search consumers.
     */
    private function searchConsumers($query)
    {
        return Consumer::where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('consumer_id', 'like', "%{$query}%")
              ->orWhere('cnic', 'like', "%{$query}%")
              ->orWhere('phone', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%");
        })
        ->with('subdivision')
        ->limit(10)
        ->get();
    }

    /**
     * Search meters.
     */
    private function searchMeters($query)
    {
        return Meter::where(function($q) use ($query) {
            $q->where('meter_no', 'like', "%{$query}%")
              ->orWhere('meter_make', 'like', "%{$query}%")
              ->orWhere('sim_number', 'like', "%{$query}%")
              ->orWhereHas('consumer', function($q) use ($query) {
                  $q->where('name', 'like', "%{$query}%");
              });
        })
        ->with(['consumer', 'subdivision'])
        ->limit(10)
        ->get();
    }

    /**
     * Search bills.
     */
    private function searchBills($query)
    {
        return Bill::where(function($q) use ($query) {
            $q->where('bill_number', 'like', "%{$query}%")
              ->orWhereHas('consumer', function($q) use ($query) {
                  $q->where('name', 'like', "%{$query}%")
                    ->orWhere('cnic', 'like', "%{$query}%");
              });
        })
        ->with(['consumer', 'subdivision'])
        ->limit(10)
        ->get();
    }

    /**
     * Search complaints.
     */
    private function searchComplaints($query)
    {
        return Complaint::where(function($q) use ($query) {
            $q->where('complaint_id', 'like', "%{$query}%")
              ->orWhere('subject', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhereHas('consumer', function($q) use ($query) {
                  $q->where('name', 'like', "%{$query}%");
              });
        })
        ->with(['consumer', 'subdivision'])
        ->limit(10)
        ->get();
    }

    /**
     * Search subdivisions.
     */
    private function searchSubdivisions($query)
    {
        return Subdivision::where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('code', 'like', "%{$query}%");
        })
        ->with('company')
        ->limit(10)
        ->get();
    }

    /**
     * Quick search API endpoint.
     */
    public function quickSearch(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [
            'consumers' => Consumer::where('name', 'like', "%{$query}%")
                ->orWhere('consumer_id', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'consumer_id', 'name', 'cnic']),
            'meters' => Meter::where('meter_no', 'like', "%{$query}%")
                ->with('consumer:id,name')
                ->limit(5)
                ->get(['id', 'meter_no', 'consumer_id']),
            'bills' => Bill::where('bill_number', 'like', "%{$query}%")
                ->with('consumer:id,name')
                ->limit(5)
                ->get(['id', 'bill_number', 'consumer_id', 'total_amount']),
            'complaints' => Complaint::where('complaint_id', 'like', "%{$query}%")
                ->orWhere('subject', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'complaint_id', 'subject', 'status']),
        ];

        return response()->json($results);
    }
}
