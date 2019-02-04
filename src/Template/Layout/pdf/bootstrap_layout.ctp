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