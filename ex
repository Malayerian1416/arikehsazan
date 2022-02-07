@forelse($projects as $project)
                    <div class="gadget_item_container owl-carousel owl-theme">
                        <div class="gadget_item bg-dark">
                        <div class="gadget_title">
                            <h6 class="iran_yekan mb-3">اطلاعات پروژه {{$project->name}}</h6>
                        </div>
                        <div class="gadget-body">
                            <div>
                                @php
                                    $start = explode("/",$project->project_start_date);
                                    $end = explode("/",$project->project_completion_date);
                                    $start = verta(implode("-",\Hekmatinasser\Verta\Verta::getGregorian($start[0],$start[1],$start[2])));
                                    $end = verta(implode("-",\Hekmatinasser\Verta\Verta::getGregorian($end[0],$end[1],$end[2])));
                                    $total_diff = $start->diffDays($end);
                                    $percent_diff = $start->diffDays();
                                    $remain = $total_diff - $percent_diff >= 1 ? $total_diff - $percent_diff : 0;
                                    $percent = ceil(($percent_diff / $total_diff) * 100) <= 100 ? ceil(($percent_diff / $total_diff) * 100) : 100;
                                    $color = ceil(($percent / 100) * 255);
                                @endphp
                                <h6 class="iran_yekan mb-3 white_color text-muted-light">زمانبندی {{$project->name}}</h6>
                                <div class="w-100 border mb-3 d-flex justify-content-center align-items-center progress_bar_container">
                                    <div class="progress_bar" style="background: rgb({{$color}},{{255-$color}},0);width: {{$percent}}%">
                                    </div>
                                    <span class="iran_yekan white_color" style="z-index: 100">{{$percent."%"}}</span>
                                </div>
                                <table class="w-100 iran_yekan table table-bordered text-center project_gadget_table">
                                    <tr>
                                        <td>{{$project->project_start_date}}</td>
                                        <td>کل روزها</td>
                                        <td>{{"$total_diff روز"}}</td>
                                    </tr>
                                    <tr>
                                        <td>لغایت</td>
                                        <td>سپری شده</td>
                                        <td>{{"$percent_diff روز"}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{$project->project_completion_date}}</td>
                                        <td>باقیمانده</td>
                                        <td>{{"$remain روز"}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                @empty
                @endforelse
