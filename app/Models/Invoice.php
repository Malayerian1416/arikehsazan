<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Collection;

class Invoice extends Model
{
    use HasFactory;
    protected $table = "invoices";
    protected $fillable = ["contract_id","user_id","is_final","number"];

    public function contract(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contract::class,"contract_id");
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"user_id");
    }
    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Unit::class,"unit_id");
    }
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceComment::class,"invoice_id");
    }
    public function signs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceSign::class,"invoice_id");
    }
    public function extras(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceExtra::class,"invoice_id");
    }
    public function deductions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceDeduction::class,"invoice_id");
    }
    public function automation_amounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceAutomationAmounts::class,"invoice_id");
    }
    public function automation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(InvoiceAutomation::class,"invoice_id");
    }
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoicePayment::class,"invoice_id");
    }
    public static function report($contract_id): \Illuminate\Support\Collection
    {
        $reports = Invoice::query()->with(["contract.contractor","contract.unit","automation_amounts","payments","extras","deductions"])->whereHas("contract",function($query) use($contract_id){
            $query->where("contracts.id","=",$contract_id);
        })->orderBy("created_at")->get();
        $final_report = [];
        $total_quantity = 0;
        $total_sum = 0;
        $total_payed = 0;
        $total_remain = 0;
        $counter = 0;
        if ($reports->isNotEmpty()) {
            $contract = Contract::query()->with("unit")->findOrFail($contract_id);
            $unit = $contract->unit->name;
            foreach ($reports as $row) {
                $main_amount = $row->automation_amounts->where("is_main", "=", 1)->first();
                if ($main_amount != null) {
                    $sum = ($main_amount->quantity * $main_amount->amount) + array_sum(array_column($row->extras->toArray(), "amount")) - array_sum(array_column($row->deductions->toArray(), "amount"));
                    $payed = array_sum(array_column($row->payments->toArray(), "amount_payed"));
                    $final_report ["details"][] = [
                        "row" => ++$counter,
                        "number" => $row->number,
                        "date" => verta($row->created_at)->format("Y/n/d"),
                        "quantity" => $main_amount->quantity,
                        "total" => number_format($sum),
                        "payment" => number_format($payed),
                        "remain" => number_format($sum - $payed)
                    ];
                    $total_quantity += $main_amount->quantity;
                    $total_sum += $sum;
                    $total_payed += $payed;
                    $total_remain += $sum - $payed;
                }
            }
            $final_report ["totals"] = [
                "total_quantity" => number_format($total_quantity),
                "total_sum" => number_format($total_sum),
                "total_payed" => number_format($total_payed),
                "total_remain" => number_format($total_remain),
                "unit" => "({$unit})"
            ];
        }
        return collect($final_report);
    }
}
