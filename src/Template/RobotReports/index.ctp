<section>
    <div class="row">
        <div class="col-md-12">

            <h3 class="text-left">
                <?php echo __('Reports');?>
            </h3>
        </div>
    </div>

    <div class="row">
        <?php foreach($robotReports as $robot_report):?>
            <div class="col-md-4">

                <?php
                    echo $this->Html->link(
                        $this->Html->div('panel panel-default',

                            $this->Html->div('row text-center', 
                                $this->Html->div('col-md-12', 
                                    $this->Html->image('robot_reports/'.$robot_report->report_icon, ['class' => 'img-responsive', 'style' => 'width:65px;margin-left: auto;margin-right: auto;display: block;']).
                                    $this->Html->tag('strong', __($robot_report->report_name), ['class' => 'text-left'])
                                    
                                ),
                                [
                                    'style' => 'padding: 20px;'
                                ] 
                            )
                        ),
                        [
                            'controller' => 'RobotReports',
                            'action' => $robot_report->report_keyword
                        ],
                        [
                            'escape' => false
                        ]
                    );
                ?>
            </div>
        <?php endforeach;?>
    </div>
</section>