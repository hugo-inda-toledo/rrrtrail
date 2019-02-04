<?php if(isset($type) && $type == 'list'):?>    
    <!DOCTYPE html>
    <html>
        <head>
            <?php echo $this->Html->charset() ?>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <?php echo $this->fetch('meta') ?>
            <title>
                Zippedi :: <?php echo $this->fetch('title') ?>
            </title>

            <!-- Core CSS - Include with every page -->
            <?php //echo $this->Html->css('application/bootstrap.css', ['fullBase' => true]) ?>
            <link rel="stylesheet" type="text/css" href="http://my.zippedi.com/css/application/bootstrap.css">
            <?php //echo $this->Html->css('application/font-awesome/css/font-awesome.css', ['fullBase' => true]) ?>
            <link rel="stylesheet" type="text/css" href="http://my.zippedi.com/css/application/font-awesome/css/font-awesome.css">

            <!-- SB Admin CSS - Include with every page -->
            <?php //echo $this->Html->css('application/sb-admin.css', ['fullBase' => true]) ?>
            <link rel="stylesheet" type="text/css" href="http://my.zippedi.com/css/application/sb-admin.css">


            <?php //echo $this->Html->css($this->Url->build('/css/application/bootstrap.css', true));?>
            <?php //echo $this->Html->css($this->Url->build('/css/font-awesome/css/font-awesome.css', true));?>
            <?php //echo $this->Html->css($this->Url->build('/css/application/sb-admin.css', true));?>

            <?php echo $this->fetch('css') ?>
        </head>

        <body style="padding-top: 0px;">

            <div id="wrapper">
                <div id="page-wrapper" style="padding: 10px 30px;min-height: 0px; margin: 0px;">
                    <?php echo $this->fetch('content') ?>
                </div>
            </div>
        </body>
    </html>
<?php else:?>
    <!DOCTYPE html>
    <html>
        <head>

            <?php echo $this->Html->charset('utf-8') ?>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <?php echo $this->fetch('meta') ?>
            <title>
                Zippedi :: <?php echo $this->fetch('title') ?>
            </title>

            <?php echo $this->Html->meta('zippedi_favicon.png', '/zippedi_favicon.png', array('type' => 'icon'));?>

            <!-- Core CSS - Include with every page -->
             <?php echo $this->Html->css('http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900') ?>

            <?php echo $this->Html->css('theme-zippedi/bootstrap.css?1422823238', ['fullBase' => true]) ?>
            <?php echo $this->Html->css('theme-zippedi/materialadmin.css?1422823243', ['fullBase' => true]) ?>
            <?php echo $this->Html->css('theme-zippedi/font-awesome.min.css?1422823239', ['fullBase' => true]) ?>
            <?php echo $this->Html->css('theme-zippedi/material-design-iconic-font.min.css?1422823240', ['fullBase' => true]) ?>
            <?php echo $this->fetch('css') ?>
        </head> 
        

        <body style="padding-top: 0px;background-color:#FFF;">
            <div id="base">
                <div id="content">
                    <?php echo $this->fetch('content') ?>
                </div>
            </div>
        </body>
    </html>
<?php endif;?>