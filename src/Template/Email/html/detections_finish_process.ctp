<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="full-text">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                       <tbody>
                                          <!-- Title -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #333333; text-align:center; line-height: 30px;" st-title="fulltext-heading">
                                                   <?php echo __("Session loaded"); ?>
                                             </td>
                                          </tr>
                                          <!-- End of Title -->
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#666666;text-align:center;line-height:30px">
                                       <?php 
                                          echo __("This is a summary of the session {0}, {1} {2} ({3})", [$data['robot_session']->session_code, $data['company']['company_name'], $data['store']['store_name'], $data['store']['store_code']]).'.'; 
                                       ?>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>

<br>

<?php if(isset($data['robot_session']) && $data['robot_session'] != null && $data['type_report'] == 'priceDifferenceReport'):?>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="full-text">
       <tbody>
          <tr>
             <td>
                <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                   <tbody>
                      <tr>
                         <td width="100%">
                            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                               <tbody>
                                  <tr>
                                     <td style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#666666;text-align:center;line-height:30px" st-content="3col-content1" width="100%" align="center" class="devicewidth">
                                        <table border="1" align="center" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <td><?php echo '% '.__("Today's fulfillment");?></td>
                                                    
                                                    <?php if(isset($data['last_robot_session']) && $data['last_robot_session'] != null):?>

                                                        <td><?php echo __("r / last session");?></td>

                                                    <?php endif;?>

                                                    <td><?php echo '# '.__('Alerts / Readed labels');?></td>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 24px;">
                                                <tr>
                                                    <td>
                                                        <?php 
                                                            $labels_percent = ($data['robot_session']->total_price_difference_detections * 100) / $data['robot_session']->total_detections;
                                                            $compliance = floatval(100 - $labels_percent);
                                                            echo round($compliance, 2).'%';
                                                        ?>
                                                    </td>

                                                    <?php if(isset($data['last_robot_session']) && $data['last_robot_session'] != null):?>
                                                        
                                                        <td>
                                                            <?php 
                                                                $last_labels_percent = ($data['last_robot_session']->total_price_difference_detections * 100) / $data['last_robot_session']->total_detections;
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

                                                    <?php endif;?>

                                                    <td>
                                                        <?php echo __('{0} / {1}', [$data['robot_session']->total_price_difference_detections, $data['robot_session']->total_detections]);?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                     </td>
                                  </tr>
                               </tbody>
                            </table>
                         </td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>
    <!-- end of full text -->
    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
       <tbody>
          <tr>
             <td>
                <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                   <tbody>
                      <tr>
                         <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                      <tr>
                         <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                      <tr>
                         <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>
<?php endif;?>

<?php if(isset($data['robot_session']) && $data['robot_session'] != null && $data['type_report'] == 'stockOutReport'):?>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="full-text">
       <tbody>
          <tr>
             <td>
                <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                   <tbody>
                      <tr>
                         <td width="100%">
                            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                               <tbody>
                                  <!-- Spacing -->
                                  <tr>
                                     <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                  </tr>
                                  <!-- Spacing -->
                                  <tr>
                                     <td style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#666666;text-align:center;line-height:30px" st-content="3col-content1" width="100%" align="center" class="devicewidth">
                                        <table border="1" align="center" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <td><?php echo '% '.__("Today's fulfillment");?></td>

                                                    <?php if(isset($data['last_robot_session']) && $data['last_robot_session'] != null):?>

                                                        <td><?php echo __("r / last session");?></td>

                                                    <?php endif;?>

                                                    <td><?php echo '# '.__('Alerts / Readed labels');?></td>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 24px;">
                                                <tr>
                                                    <td>
                                                        <?php 
                                                            $labels_percent = ($data['robot_session']->total_stock_alert_detections * 100) / $data['robot_session']->total_detections;
                                                            $compliance = floatval(100 - $labels_percent);
                                                            echo round($compliance, 2).'%';
                                                        ?>
                                                    </td>

                                                    <?php if(isset($data['last_robot_session']) && $data['last_robot_session'] != null):?>
                                                        
                                                        <td>
                                                            <?php 
                                                                $last_labels_percent = ($data['last_robot_session']->total_stock_alert_detections * 100) / $data['last_robot_session']->total_detections;
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

                                                    <?php endif;?>

                                                    <td>
                                                        <?php echo __('{0} / {1}', [$data['robot_session']->total_stock_alert_detections, $data['robot_session']->total_detections]);?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                     </td>
                                  </tr>
                                  <tr>
                                     <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                  </tr>
                                  <tr>
                                     <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #889098; text-align:center; line-height: 24px;" st-content="3col-content1" width="100%" align="center" class="devicewidth">

                                        <?php //echo $this->html->link(__('Rate %s now', strtolower($work_order_data['HelperData']['Information']['name'])).'!', array('controller' => 'movements', 'action' => 'my-feedbacks/to_do', 'full_base' => true), array('style' => 'text-decoration:none; background-color: #4caf50; color:#FFF; border: 1px solid #419645; border-radius: 3px 3px 3px; padding: 6px 15px;')); ?>
                                     </td>
                                  </tr>
                               </tbody>
                            </table>
                         </td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>
    <!-- end of full text -->
    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
       <tbody>
          <tr>
             <td>
                <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                   <tbody>
                      <tr>
                         <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                      <tr>
                         <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                      <tr>
                         <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>
<?php endif;?>

<!-- End of seperator -->   
<!-- 3 Start of Columns -->
<?php if(isset($data['chart_array']['price_differences_detections_url'])):?>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
       <tbody>
          <tr>
             <td>
                <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                   <tbody>
                      <tr>
                         <td width="100%">
                            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                               <tbody>
                                  <tr>
                                     <td>
                                        <table width="100%" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                           <tbody>
                                              <!-- image 2 -->
                                              <tr>
                                                 <td width="100%" align="center" class="devicewidth">
                                                    <img src="<?php echo $data['chart_array']['price_differences_detections_url'];?>" alt="" border="0" width="480" style="display:block; border:none; outline:none; text-decoration:none;border-radius: 15px 15px 15px;width: 600px;border: 3px solid #CCC;">
                                                 </td>
                                              </tr>
                                           </tbody>
                                        </table>
                                     </td>
                                     <!-- spacing -->
                                     <!-- end of spacing -->
                                  </tr>
                               </tbody>
                            </table>
                         </td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>

    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
       <tbody>
          <tr>
             <td>
                <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                   <tbody>
                      <tr>
                         <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                      <tr>
                         <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                      <tr>
                         <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>
    <!-- End of seperator -->   
<?php endif;?>

<?php if(isset($data['chart_array']['stock_alert_detections_url'])):?>
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
       <tbody>
          <tr>
             <td>
                <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                   <tbody>
                      <tr>
                         <td width="100%">
                            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                               <tbody>
                                  <tr>
                                     <td>
                                        <table width="100%" align="left" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                           <tbody>
                                              <!-- image 2 -->
                                              <tr>
                                                 <td width="100%" align="center" class="devicewidth">
                                                    <img src="<?php echo $data['chart_array']['stock_alert_detections_url'];?>" alt="" border="0" width="480" style="display:block; border:none; outline:none; text-decoration:none;border-radius: 15px 15px 15px;width: 600px;border: 3px solid #CCC;">
                                                 </td>
                                              </tr>
                                           </tbody>
                                        </table>
                                     </td>
                                     <!-- spacing -->
                                     <!-- end of spacing -->
                                  </tr>
                               </tbody>
                            </table>
                         </td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>

    <!-- Start of seperator -->
    <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
       <tbody>
          <tr>
             <td>
                <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                   <tbody>
                      <tr>
                         <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                      <tr>
                         <td width="550" align="center" height="1" bgcolor="#d1d1d1" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                      <tr>
                         <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>
    <!-- End of seperator -->   
<?php endif;?>

<!-- Start Full Text -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="full-text">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                     <tr>
                        <td width="100%" align="center" class="devicewidth">
                           <img src="cid:medkit-id" alt="" border="0" width="90" style="display:block; border:none; outline:none; text-decoration:none;">
                        </td>
                     </tr>
                  <tr>
                     <td width="100%">
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                       <tbody>
                                          <!-- End of spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #666666; text-align:center; line-height: 30px;" st-content="fulltext-content">

                                                <?php //echo __('Once again, thanks for your confidence in Helperland and hope to see you soon requesting more help through our community').'.';?>

                                                <?php //echo '<br><br>'.__('Have a nice day').'!'?>
                                             </td>
                                          </tr>
                                          <!-- End of content -->
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>