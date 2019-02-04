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
                                                <?php echo __('Blocked Assortment Report'); ?>
                                             </td>
                                          </tr>
                                          <!-- End of Title -->
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #666666; text-align:center; line-height: 30px;" st-content="fulltext-content">
                                                <?php
                                                   echo __('Attached are links to get the blocked assortment report of {0} {1} ({2}) of the day {3}', [$data['company']['company_name'], $data['store']['store_name'], $data['store']['store_code'], $data['robot_session']['calendar_date']->format('d-m H:i')]);
                                                ?>
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
                                                <td rowspan="2"><?php echo __("Section");?></td>
                                                <td colspan="2"><?php echo __('Downloads');?></td>
                                             </tr>
                                             <tr>
                                                <td>PDF</td>
                                                <td>EXCEL</td>
                                             </tr>
                                         </thead>
                                         <tbody style="font-size: 18px;">
                                             <?php foreach($data['sections'] as $section):?>
                                                <tr>
                                                    <td>
                                                        <?php 
                                                            echo $section->section_name
                                                        ?>
                                                    </td>

                                                    <td>
                                                         <a href="<?php echo 'http://my.zippedi.com/reports/blockedAssortmentReport/download/pdf/'.$data['robot_session']->id.'/'.$section->id.'/all';?>" align="center">
                                                            <img src="cid:pdf-id" alt="" border="0" width="24" style="display:inline; border:none; outline:none; text-decoration:none;" align="center">
                                                         </a>
                                                    </td>

                                                    <td>
                                                         <a href="<?php echo 'http://my.zippedi.com/reports/blockedAssortmentReport/download/xlsx/'.$data['robot_session']->id.'/'.$section->id.'/all';?>" align="center">
                                                            <img src="cid:excel-id" alt="" border="0" width="24" style="display:inline; border:none; outline:none; text-decoration:none;" align="center">
                                                         </a>
                                                    </td>
                                                </tr>
                                             <?php endforeach;?>
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