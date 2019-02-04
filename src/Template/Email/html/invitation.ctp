<!-- ONE COLUMN SECTION -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" align="center" class="section-padding">
            <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="center" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding-copy"><?php echo __("{0}, you have received an invitation to join MyZippedi", $invitation->name).'!';?></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy"><?php echo __('You have been invited to join to my.zippedi.com, click on the button below to complete your necessary information to continue').'.';?></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding: 20px 0 0 0; font-size: 13px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #920c0c;" class="padding-copy">
                                                <small>
                                                <?php echo __('You have 24 hours to complete this registration from the time of sending this email, otherwise the invitation will be marked as expired').'.';?>
                                                </small>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                        <tr>
                                            <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                                                <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                                                    <tr>
                                                        <td align="center">
                                                            <?php echo 
                                                                $this->Html->link(
                                                                    __('Sign up now'),
                                                                    [
                                                                        'controller' => 'Users',
                                                                        'action' => 'confirmInvitation', $invitation->hash_code,
                                                                        'plugin' => false,
                                                                        '_full' => true
                                                                    ],
                                                                    [
                                                                        'target' => '_blank',
                                                                        'style' => 'font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;',
                                                                        'class' => 'mobile-button'
                                                                    ]
                                                                );
                                                            ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>