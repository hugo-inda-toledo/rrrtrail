<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo $this->fetch('meta') ?>
        <title>
            Zippedi :: <?php echo $this->fetch('title') ?>
        </title>

        <?php echo $this->Html->meta('zippedi_favicon.png', '/zippedi_favicon.png', array('type' => 'icon'));?>

        <!-- Core CSS - Include with every page -->
        <?php echo $this->Html->css('application/bootstrap.css') ?>
        <?php echo $this->Html->css('application/font-awesome/css/font-awesome.css') ?>

        <!-- SB Admin CSS - Include with every page -->
        <?php echo $this->Html->css('application/sb-admin.css') ?>

        <?php echo $this->fetch('css') ?>
    </head>

    <body>

        <div class="container">
            <?php echo $this->fetch('content') ?>
        </div>

        <!-- Core Scripts - Include with every page -->
        <?php echo $this->Html->script('application/jquery-1.10.2.js');?>
        <?php echo $this->Html->script('application/bootstrap.min.js');?>
        <?php echo $this->Html->script('application/plugins/metisMenu/jquery.metisMenu.js');?>

        <!-- SB Admin Scripts - Include with every page -->
        <?php echo $this->Html->script('application/sb-admin.js');?>

        <?php echo $this->fetch('script') ?>
        <?php echo $this->fetch('scriptBottom')?>

    </body>

</html>