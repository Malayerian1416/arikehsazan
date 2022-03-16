@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('idle')
{{--    <div class="gadget_3 d-flex flex-row align-items-stretch justify-content-start">--}}
{{--        <h6 class="gadget_header p-3 text-center bg-dark">--}}
{{--            <span class="iran_yekan white_color" style="font-size: 1.3rem">اطلاعات پروژه ها</span>--}}
{{--        </h6>--}}
{{--        <div class="owl-carousel owl-theme" style="width: calc(100% - 35px)">--}}
{{--            @forelse($projects as $project)--}}
{{--                <div class="item w-100 p-3">--}}
{{--                    @php--}}
{{--                        $start = explode("/",$project->project_start_date);--}}
{{--                        $end = explode("/",$project->project_completion_date);--}}
{{--                        $start = verta(implode("-",\Hekmatinasser\Verta\Verta::getGregorian($start[0],$start[1],$start[2])));--}}
{{--                        $end = verta(implode("-",\Hekmatinasser\Verta\Verta::getGregorian($end[0],$end[1],$end[2])));--}}
{{--                        $total_diff = $start->diffDays($end);--}}
{{--                        $percent_diff = $start->diffDays();--}}
{{--                        $remain = $total_diff - $percent_diff >= 1 ? $total_diff - $percent_diff : 0;--}}
{{--                        $percent = ceil(($percent_diff / $total_diff) * 100) <= 100 ? ceil(($percent_diff / $total_diff) * 100) : 100;--}}
{{--                        $color = ceil(($percent / 100) * 255);--}}
{{--                        $invoice_sum = 0;--}}
{{--                        $worker_sum = 0;--}}
{{--                    @endphp--}}
{{--                    <div class="gadget_title">--}}
{{--                        <h6 class="iran_yekan mb-4" style="font-size: 1.5rem">{{$project->name}}</h6>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <h6 class="iran_yekan white_color text-muted-light">مدت زمان سپری شده</h6>--}}
{{--                        <div class="w-100 border mb-3 d-flex justify-content-center align-items-center progress_bar_container">--}}
{{--                            <div class="progress_bar" style="background: rgb({{$color}},{{255-$color}},0);width: {{$percent}}%">--}}
{{--                            </div>--}}
{{--                            <span class="iran_yekan" style="z-index: 100">{{$percent."%"}}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <hr/>--}}
{{--                    <div class="d-flex align-items-center justify-content-between">--}}
{{--                        <div>--}}
{{--                            <h6 class="iran_yekan mb-3 white_color text-muted-light">کل هزینه های وضعیتی</h6>--}}
{{--                            <div class="mb-3">--}}
{{--                                <h6 class="iran_yekan" style="font-size: 1.5rem">--}}
{{--                                    @forelse($project->contracts as $contract)--}}
{{--                                        @forelse($contract->invoices as $invoice)--}}
{{--                                            @php--}}
{{--                                                $invoice_sum += array_sum($invoice->payments->pluck("amount_payed")->toArray())--}}
{{--                                            @endphp--}}
{{--                                        @empty--}}
{{--                                        @endforelse--}}
{{--                                    @empty--}}
{{--                                    @endforelse--}}
{{--                                    {{number_format($invoice_sum)." ریال"}}--}}
{{--                                </h6>--}}
{{--                            </div>--}}
{{--                            <hr/>--}}
{{--                            <h6 class="iran_yekan mb-3 white_color text-muted-light">کل هزینه های کارگری</h6>--}}
{{--                            <div>--}}
{{--                                <h6 class="iran_yekan" style="font-size: 1.5rem">--}}
{{--                                    @forelse($project->worker_automations as $worker_automation)--}}
{{--                                        @if($worker_automation->payments != null)--}}
{{--                                            @php--}}
{{--                                                $worker_sum += $worker_automation->payments->amount_payed;--}}
{{--                                            @endphp--}}
{{--                                        @endif--}}
{{--                                    @empty--}}
{{--                                    @endforelse--}}
{{--                                    {{number_format($worker_sum)." ریال"}}--}}
{{--                                </h6>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <table class="w-100 iran_yekan table table-bordered text-center project_gadget_table">--}}
{{--                                <tr>--}}
{{--                                    <td>{{$project->project_start_date}}</td>--}}
{{--                                    <td>کل روزها</td>--}}
{{--                                    <td>{{"$total_diff روز"}}</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>لغایت</td>--}}
{{--                                    <td>سپری شده</td>--}}
{{--                                    <td>{{"$percent_diff روز"}}</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>{{$project->project_completion_date}}</td>--}}
{{--                                    <td>باقیمانده</td>--}}
{{--                                    <td>{{"$remain روز"}}</td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @empty--}}
{{--                <h6 class="iran_yekan" style="font-size: 1.5rem">پروژه ای یافت نشد!</h6>--}}
{{--            @endforelse--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="gadget_3 d-flex flex-row align-items-stretch justify-content-start">--}}
{{--        <h6 class="gadget_header p-3 text-center bg-dark">--}}
{{--            <span class="iran_yekan white_color" style="font-size: 1.3rem">اطلاعات پیمان ها</span>--}}
{{--        </h6>--}}
{{--        <div class="owl-carousel owl-theme" style="width: calc(100% - 35px)">--}}
{{--            @forelse($contracts as $contract)--}}
{{--                <div class="item w-100 p-3 h-100">--}}
{{--                    @php--}}
{{--                        $invoice_sum = 0;--}}
{{--                        $active_count = 0;--}}
{{--                        $final_invoice = 'صادر نشده';--}}
{{--                    @endphp--}}
{{--                    @forelse($contract->invoices as $invoice)--}}
{{--                        @php--}}
{{--                            $invoice_sum += array_sum($invoice->payments->pluck("amount_payed")->toArray());--}}
{{--                            if ($invoice->payments->isEmpty())--}}
{{--                                $active_count++;--}}
{{--                            if ($invoice->is_final == 1)--}}
{{--                                $final_invoice = 'صادر شده';--}}
{{--                        @endphp--}}
{{--                    @empty--}}
{{--                    @endforelse--}}
{{--                    <div class="gadget_title">--}}
{{--                        <h6 class="iran_yekan" style="font-size: 1.5rem">{{$contract->name}}</h6>--}}
{{--                    </div>--}}
{{--                    <div class="gadget_title">--}}
{{--                        <h6 class="iran_yekan" style="font-size: 1.2rem">پروژه {{$contract->project->name}}</h6>--}}
{{--                    </div>--}}
{{--                    <div class="gadget_title">--}}
{{--                        <h6 class="iran_yekan mb-4" style="font-size: 1.2rem">پیمانکار {{$contract->contractor->name}}</h6>--}}
{{--                    </div>--}}
{{--                    <hr/>--}}
{{--                    <div class="gadget_title">--}}
{{--                        <h6 class="iran_yekan text-muted">وضعیت های پرداخت نشده : {{$active_count}}</h6>--}}
{{--                    </div>--}}
{{--                    <hr/>--}}
{{--                    <div class="gadget_title">--}}
{{--                        <h6 class="iran_yekan text-muted">وضعیت نهایی : {{$final_invoice}}</h6>--}}
{{--                    </div>--}}
{{--                    <hr/>--}}
{{--                    <h6 class="iran_yekan white_color text-muted-light">کل پرداختی</h6>--}}
{{--                    <div class="mb-3">--}}
{{--                        <h6 class="iran_yekan" style="font-size: 1.5rem">--}}
{{--                            {{number_format($invoice_sum)." ریال"}}--}}
{{--                        </h6>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @empty--}}
{{--                <h6 class="iran_yekan" style="font-size: 1.5rem">پروژه ای یافت نشد!</h6>--}}
{{--            @endforelse--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="gadget_3 d-flex flex-row align-items-stretch justify-content-start">--}}
{{--        <h6 class="gadget_header p-3 text-center bg-dark">--}}
{{--            <span class="iran_yekan white_color" style="font-size: 1.3rem">اطلاعات پیمانکاران</span>--}}
{{--        </h6>--}}
{{--        <div class="owl-carousel owl-theme" style="width: calc(100% - 35px)">--}}
{{--            @forelse($contractors as $contractor)--}}
{{--                <div class="item w-100 p-3 h-100">--}}
{{--                    @php--}}
{{--                        $pay_sum = 0;--}}
{{--                        $invoice_sum = 0;--}}
{{--                        $total_remain = 0;--}}
{{--                    @endphp--}}
{{--                    @forelse($contractor->docs as $doc)--}}
{{--                        @if($doc->amount <= 0)--}}
{{--                            @php--}}
{{--                                $pay_sum += -($doc->amount);--}}
{{--                            @endphp--}}
{{--                        @elseif($doc->amount > 0)--}}
{{--                            @php--}}
{{--                                $invoice_sum += $doc->amount;--}}
{{--                            @endphp--}}
{{--                        @endif--}}
{{--                    @empty--}}
{{--                    @endforelse--}}
{{--                    <div class="gadget_title">--}}
{{--                        <h6 class="iran_yekan mb-4" style="font-size: 1.5rem">{{$contractor->name}}</h6>--}}
{{--                    </div>--}}
{{--                    <h6 class="iran_yekan white_color text-muted-light">کل بستانکاری</h6>--}}
{{--                    <div class="mb-3">--}}
{{--                        <h6 class="iran_yekan" style="font-size: 1.5rem">--}}
{{--                            {{number_format($invoice_sum)." ریال"}}--}}
{{--                        </h6>--}}
{{--                    </div>--}}
{{--                    <hr/>--}}
{{--                    <h6 class="iran_yekan white_color text-muted-light">کل پرداختی</h6>--}}
{{--                    <div class="mb-3">--}}
{{--                        <h6 class="iran_yekan" style="font-size: 1.5rem">--}}
{{--                            {{number_format($pay_sum)." ریال"}}--}}
{{--                        </h6>--}}
{{--                    </div>--}}
{{--                    <hr/>--}}
{{--                    <h6 class="iran_yekan white_color text-muted-light">مانده حساب</h6>--}}
{{--                    <div class="mb-3">--}}
{{--                        <h6 class="iran_yekan" style="font-size: 1.5rem">--}}
{{--                            {{number_format($invoice_sum - $pay_sum)." ریال"}}--}}
{{--                        </h6>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @empty--}}
{{--                <h6 class="iran_yekan" style="font-size: 1.5rem">پروژه ای یافت نشد!</h6>--}}
{{--            @endforelse--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
