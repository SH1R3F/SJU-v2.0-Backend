<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\InvoiceResource;
use App\Http\Controllers\Api\Admin\ExcelController;

class InvoiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-member', ['only' => ['index', 'show', 'export']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = Invoice::filter($request)->sortData($request)->whereNotNull('invoice_number')->offset($request->perPage * $request->page)->paginate($request->perPage);

        $admin = Auth::guard('api-admins')->user();
        if ($admin->branch_id) {
            // This admin is associated with a branch. Only show him invoices of members of this branch!
            $invoices = Invoice::whereHas('member', function ($query) use ($admin) {
                $query->where('branch', $admin->branch_id);
            })->filter($request)->sortData($request)->whereNotNull('invoice_number')->offset($request->perPage * $request->page)->paginate($request->perPage);
        }

        return response()->json([
            'total'    => Invoice::filter($request)->get()->count(),
            'invoices' => InvoiceResource::collection($invoices)
        ]);
    }

    /**
     * Export a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $invoices = Invoice::filter($request)->sortData($request)->whereNotNull('invoice_number')->get();

        $admin = Auth::guard('api-admins')->user();
        if ($admin->branch_id) {
            // This admin is associated with a branch. Only show him invoices of members of this branch!
            $invoices = Invoice::whereHas('member', function ($query) use ($admin) {
                $query->where('branch', $admin->branch_id);
            })->filter($request)->sortData($request)->whereNotNull('invoice_number')->get();
        }

        $cells = array(
            'A1' => 'م',
            'B1' => 'رقم الفاتورة',
            'C1' => 'العضو',
            'D1' => 'المبلغ',
            'E1' => 'طريقة الدفع',
            'F1' => 'الحالة',
            'G1' => 'معرف الدفع',
        );

        $cells_keys = array(
            'A' => 'counter',
            'B' => 'invoice_number',
            'C' => 'member',
            'D' => 'amount',
            'E' => 'payment_method',
            'F' => 'status',
            'G' => 'order_ref',
        );

        // Build excel cells
        $counter = 2;
        foreach ($invoices as $invoice) {
            foreach ($cells_keys as $key => $val) {
                switch ($val) {
                    case 'counter':
                        $cells[$key . $counter] = $counter - 1;
                        break;

                    case 'invoice_number':
                        $cells[$key . $counter] = $invoice->invoice_number;
                        break;

                    case 'member':
                        $cells[$key . $counter] = @$invoice->member->fullName;
                        break;

                    case 'amount':
                        $cells[$key . $counter] = $invoice->amount;
                        break;

                    case 'payment_method':
                        $cells[$key . $counter] = $invoice->payment_method === 2 ? 'MADA' : 'VISA_MASTER';
                        break;

                    case 'status':
                        $cells[$key . $counter] = $invoice->status === 1 ? 'مدفوعة' : 'غير مدفوعة';
                        break;

                    case 'order_ref':
                        $cells[$key . $counter] = $invoice->order_ref;
                        break;
                }
            }
            $counter++;
        }

        // Create the excel file
        return app(ExcelController::class)->create('invoices', $cells);
    }

    /**
     * Display the specified resource.
     *
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {

        $admin = Auth::guard('api-admins')->user();
        if ($admin->branch_id) {
            // This admin is associated with a branch. Only allow him to see invoices of members of his branch!
            if ($invoice->member->branch !== $admin->branch_id) {
                abort(403);
            }
        }
        return new InvoiceResource($invoice);
    }
}
