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
                                            <td align="left" style="padding: 20px 0 0 0; font-size: 13px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333;" class="padding-copy">

                                                <?php if(count($sessions_info)):?>

                                                    <h2>Últimas sesiones procesadas.</h2>
                                                    <br>

                                                    <?php foreach($sessions_info as $session):?>

                                                        <h3><?php echo __('{0} - {1} ({2})', [$session['company_name'], $session['store_name'], $session['store_code']]); ?></h3>

                                                        <?php if($session['has_session'] == 1):?>

                                                            <h4><?php echo __('Fecha: {0} (Código: {1})', [$session['session_date'], $session['session_code']]); ?></h4>

                                                            <h5>
                                                                Información general
                                                            </h5>

                                                            <ul style="text-align:left;list-style:none;">
                                                               <li>Total de flejes vistos: <?php echo $session['total_detections'];?></li>
                                                            </ul>

                                                            <h5>
                                                                Reporte de Surtido
                                                            </h5>

                                                            <ul style="text-align:left;list-style:none;">
                                                               <li>Fecha de inicio: <?php echo $session['assortment_processing_date'];?></li>
                                                               <li>Fecha de termino: <?php echo $session['assortment_finished_date'];?></li>
                                                               <li>Total de productos catalogados: <?php echo $session['total_catalogs'];?></li>
                                                               <li>Total de productos correctamente leídos: <?php echo ($session['total_catalog_readed_products'] != '') ? $session['total_catalog_readed_products'] : __('No data');?></li>
                                                               <li>Total de productos no leídos: <?php echo ($session['total_catalog_unreaded_products'] != '') ? $session['total_catalog_unreaded_products'] : __('No data');?></li>
                                                               <li>Total de productos correctamente leídos bloqueados: <?php echo ($session['total_catalog_readed_and_blocked_products'] != '') ? $session['total_catalog_readed_and_blocked_products'] : __('No data');?></li>
                                                               <li>Total de productos no leídos bloqueados: <?php echo ($session['total_catalog_unreaded_and_blocked_products'] != '') ? $session['total_catalog_unreaded_and_blocked_products'] : __('No data');?></li>
                                                            </ul>

                                                            <h5>
                                                                Diferencias de precio
                                                            </h5>

                                                            <ul style="text-align:left;list-style:none;">
                                                               <li>Fecha de inicio: <?php echo $session['price_differences_labels_processing_date'];?></li>
                                                               <li>Fecha de termino: <?php echo $session['price_differences_labels_finished_date'];?></li>
                                                               <li>Flejes con diferencia de precio: <?php echo $session['total_price_differences_labels'];?></li>
                                                               <li>Productos con diferencia de precio: <?php echo $session['total_price_differences_products'];?></li>
                                                            </ul>

                                                            <h5>
                                                                Alertas de reposición
                                                            </h5>

                                                            <ul style="text-align:left;list-style:none;">
                                                               <li>Fecha de inicio: <?php echo $session['facing_labels_processing_date'];?></li>
                                                               <li>Fecha de termino: <?php echo $session['facing_labels_finished_date'];?></li>
                                                               <li>Detecciones con alerta de reposición: <?php echo $session['total_stock_alert_detections'];?></li>
                                                               <li>Productos con alerta de reposición: <?php echo $session['total_stock_alert_products'];?></li>
                                                            </ul>

                                                        <?php else:?>

                                                            <ul style="text-align:left;list-style:none;">
                                                                <li>No se recibieron datos</li>
                                                            </ul>

                                                        <?php endif;?>

                                                        <hr>
                                                        <br><br>


                                                    <?php endforeach;?>

                                                <?php endif;?>
                                                
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