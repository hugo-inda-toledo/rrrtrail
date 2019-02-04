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
                                                <?php echo __('{0}, you have requested to change your password', $user->name).'.'; ?>
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
<!-- End of seperator -->   
<!-- 3 Start of Columns -->
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
                                                <img src="cid:lock-id" alt="" border="0" width="50" style="display:block; border:none; outline:none; text-decoration:none;">
                                             </td>
                                          </tr>
                                          <!-- end of image2 -->
                                          <tr>
                                             <td>
                                                <!-- start of text content table -->  
                                                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidthinner">
                                                   <tbody>
                                                      <!-- Spacing -->
                                                      <tr>
                                                         <td width="100%" height="15" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                      </tr>
                                                      <!-- Spacing -->
                                                      <!-- title2 -->
                                                      <tr>
                                                         <td style="font-family: Helvetica, arial, sans-serif; font-size: 18px; color: #666666; text-align:center; line-height: 24px;" st-title="3col-title1"> 
                                                              <?php echo __("If you have forgotten your password don't worry, you can change it by clicking on the bottom button").'.';?>
                                                         </td>
                                                      </tr>
                                                      <!-- end of title2 -->
                                                      <!-- Spacing -->
                                                      <tr>
                                                         <td width="100%" height="15" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                                      </tr>
                                                      <!-- Spacing -->
                                                      <!-- content2 -->
                                                      <tr>
                                                         <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #889098; text-align:center; line-height: 24px;" st-content="3col-content1">
                                                            <?php echo $this->html->link(__('Change your password'), array('controller' => 'Users', 'action' => 'resetPasswordToken', $user->recovery_token, '_full' => true), array('style' => 'text-decoration:none; background-color: #4caf50; color:#FFF; border: 1px solid #419645; border-radius: 3px 3px 3px; padding:5px;')); ?>
                                                         </td>
                                                      </tr>
                                                      <!-- end of content2 -->
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                          <!-- end of text content table -->
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
<!-- Start Full Text -->
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
                                          <!-- End of spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #666666; text-align:center; line-height: 30px;" st-content="fulltext-content">

                                                <?php echo __('Remember that you have 60 minutes to make this change otherwise the request will be automatically expire').'.';?>
                                                <br>
                                                <?php echo '<br>'.__('Have a nice day').'!'?>
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