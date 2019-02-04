<ul class="timeline collapse-lg timeline-hairline no-shadow">
	<!--<li class="timeline-inverted">
		<div class="timeline-circ style-success"></div>
		<div class="timeline-entry">
			<div class="card style-default-bright">
				<div class="card-body small-padding">
					<small class="text-uppercase text-primary pull-right">January, 2014 - Present</small>
					<p>
						<span class="text-lg text-medium">Manager director</span><br>
						<span class="text-lg text-light">Web Design Studios</span>
					</p>
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc vehicula, magna et bibendum malesuada, purus augue suscipit dolor, vitae fringilla dui nibh non lectus. Curabitur in pellentesque tortor. Nunc posuere vestibulum augue, quis posuere orci blandit vitae. Suspendisse dignissim elit dui, ac dictum felis interdum nec.
					</p>
				</div>
			</div>
		</div>
	</li>-->
	<li>
		<div class="timeline-circ style-info"></div>
		<div class="timeline-entry">
			<div class="card style-default-bright">
				<div class="card-body small-padding">
					<small class="text-uppercase text-primary pull-right">
						<?php echo ($robot_sessions[0]->price_differences_labels_finished_date != null) ? __('Report uploaded {0}', $robot_sessions[0]->price_differences_labels_finished_date->timeAgoInWords(['accuracy' => ['month' => 'month'], 'end' => '1 day'])) : '';?>
					</small>
					<p>
						<span class="text-lg text-medium"><?php echo __('Price differences report');?></span><br>
						<span class="text-lg text-light"></span>
					</p>
					<p>
						<div class="table-responsive">
							<table class="table table-condensed text-center">
	                            <thead>
	                                <tr>
	                                    <td><?php echo '% '.__("Today's fulfillment");?></td>
	                                    <td><?php echo __("r / last session");?></td>
	                                    <td><?php echo '# '.__('Alerts / Readed labels');?></td>
	                                </tr>
	                            </thead>
	                            <tbody style="font-size: 24px;">
	                                <tr>
	                                    <td>
	                                        <?php 
	                                            $labels_percent = ($robot_sessions[0]->total_price_difference_detections * 100) / $robot_sessions[0]->total_detections;
	                                            $compliance = floatval(100 - $labels_percent);
	                                            echo round($compliance, 2).'%';
	                                        ?>
	                                    </td>

	                                    <td>
	                                        <?php 
	                                            $last_labels_percent = ($robot_sessions[1]->total_price_difference_detections * 100) / $robot_sessions[1]->total_detections;
	                                            $last_compliance = floatval(100 - $last_labels_percent);
	                                            $relative_number = round($compliance - $last_compliance, 2);

	                                            if($relative_number < 0){ 
	                                              echo $relative_number; 
	                                            }
	                                            else{ 
	                                              echo "+".$relative_number; 
	                                            }
	                                        ?>
	                                    </td>

	                                    <td>
	                                        <?php echo __('{0} / {1}', [$robot_sessions[0]->total_price_difference_detections, $robot_sessions[0]->total_detections]);?>
	                                    </td>
	                                </tr>
	                            </tbody>
	                        </table>
	                    </div>
					</p>
				</div>
			</div>
		</div>
	</li>
	<li>
		<div class="timeline-circ style-danger"></div>
		<div class="timeline-entry">
			<div class="card style-default-bright">
				<div class="card-body small-padding">
					<small class="text-uppercase text-primary pull-right">
						<?php echo ($robot_sessions[0]->facing_labels_finished_date != null) ? __('Report uploaded {0}', $robot_sessions[0]->facing_labels_finished_date->timeAgoInWords(['accuracy' => ['month' => 'month'], 'end' => '1 day'])) : '';?>
					</small>
					<p>
						<span class="text-lg text-medium"><?php echo __('Stocks alert report');?></span><br>
						<span class="text-lg text-light"></span>
					</p>
					<p>
						<div class="table-responsive">
							<table class="table table-condensed text-center">
	                            <thead>
	                                <tr>
	                                    <td><?php echo '% '.__("Today's fulfillment");?></td>
	                                    <td><?php echo __("r / last session");?></td>
	                                    <td><?php echo '# '.__('Alerts / Readed labels');?></td>
	                                </tr>
	                            </thead>
	                            <tbody style="font-size: 24px;">
	                                <tr>
	                                    <td>
	                                        <?php 
	                                            $labels_percent = ($robot_sessions[0]->total_stock_alert_detections * 100) / $robot_sessions[0]->total_detections;
	                                            $compliance = floatval(100 - $labels_percent);
	                                            echo round($compliance, 2).'%';
	                                        ?>
	                                    </td>

	                                    <td>
	                                        <?php 
	                                            $last_labels_percent = ($robot_sessions[1]->total_stock_alert_detections * 100) / $robot_sessions[1]->total_detections;
	                                            $last_compliance = floatval(100 - $last_labels_percent);
	                                            $relative_number = round($compliance - $last_compliance, 2);

	                                            if($relative_number < 0){ 
	                                              echo $relative_number; 
	                                            }
	                                            else{ 
	                                              echo "+".$relative_number; 
	                                            }
	                                        ?>
	                                    </td>

	                                    <td>
	                                        <?php echo __('{0} / {1}', [$robot_sessions[0]->total_stock_alert_detections, $robot_sessions[0]->total_detections]);?>
	                                    </td>
	                                </tr>
	                            </tbody>
	                        </table>
	                    </div>
					</p>
				</div>
			</div>
		</div>
	</li>
</ul>