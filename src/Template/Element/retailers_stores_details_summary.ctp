<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">
                        <div id="donut-today-session" class="height-9" data-colors="#8bc34a,#36a29d,#b34c4c"></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body no-padding">
                                        <div class="alert alert-callout alert-success no-margin">
                                            <strong class="pull-right text-success text-lg">0,38% <i class="md md-trending-up"></i></strong>
                                            <strong class="text-xl"><?php echo $robot_sessions[0]->total_detections;?></strong><br/>
                                            <span class="opacity-50"><?php echo __('Readed labels');?></span>
                                            <div class="stick-bottom-left-right">
                                                <div class="height-2 sparkline-revenue" data-line-color="#bdc1c1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body no-padding">
                                        <div class="alert alert-callout alert-info no-margin">
                                            <strong class="pull-right text-warning text-lg">0,01% <i class="md md-swap-vert"></i></strong>
                                            <strong class="text-xl"><?php echo $robot_sessions[0]->total_price_difference_detections;?></strong><br/>
                                            <span class="opacity-50"><?php echo __('Price differences');?></span>
                                            <div class="stick-bottom-right">
                                                <div class="height-1 sparkline-visits" data-bar-color="#e5e6e6"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body no-padding">
                                        <div class="alert alert-callout alert-danger no-margin">
                                            <strong class="pull-right text-warning text-lg">0,01% <i class="md md-swap-vert"></i></strong>
                                            <strong class="text-xl"><?php echo $robot_sessions[0]->total_stock_alert_detections;?></strong><br/>
                                            <span class="opacity-50"><?php echo __('Stocks alerts');?></span>
                                            <div class="stick-bottom-right">
                                                <div class="height-1 sparkline-visits" data-bar-color="#e5e6e6"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <?php echo $this->Html->tag('h3', __('Evolutionary graph of the last 15 sessions'), ['class' => 'text-center']); ?>
                <div id="morris-area-graph" class="height-10" data-colors="#8bc34a,#36a29d,#b34c4c"></div>
            </div><!--end .card-body -->
        </div><!--end .card -->
    </div>
</div>