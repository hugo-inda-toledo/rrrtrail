<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php echo $this->fetch('meta') ?>
        <title>
            Zippedi :: <?php echo $this->fetch('title') ?>
        </title>

        <?php echo $this->Html->meta('zippedi_favicon.png', '/zippedi_favicon.png', array('type' => 'icon'));?>

        <!-- Core CSS - Include with every page -->
        <?php //echo $this->Html->css('http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900') ?>

        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

        <?php echo $this->Html->css('theme-zippedi/bootstrap.css?1422792965') ?>
        <?php echo $this->Html->css('theme-zippedi/materialadmin.css?1422792965') ?>
        <?php echo $this->Html->css('theme-zippedi/font-awesome.min.css?1422529194') ?>
        <?php echo $this->Html->css('theme-zippedi/material-design-iconic-font.min.css?1421434286') ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script type="text/javascript" src="../../assets/js/libs/utils/html5shiv.js?1403934957"></script>
        <script type="text/javascript" src="../../assets/js/libs/utils/respond.min.js?1403934956"></script>
        <![endif]-->

        <?php echo $this->fetch('css') ?>
    </head>
    <body class="menubar-hoverable header-fixed " style="font-family: 'Raleway', sans-serif;">

        <?php echo $this->Flash->render() ?>
        <?php echo $this->fetch('content') ?>
        

        <!-- BEGIN JAVASCRIPT -->
        <?php echo $this->Html->script('theme-zippedi/libs/jquery/jquery-1.11.2.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/jquery/jquery-migrate-1.2.1.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/bootstrap/bootstrap.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/spin.js/spin.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/autosize/jquery.autosize.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/libs/nanoscroller/jquery.nanoscroller.min.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/App.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppNavigation.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppOffcanvas.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppCard.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppForm.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppNavSearch.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/source/AppVendor.js');?>
        <?php echo $this->Html->script('theme-zippedi/core/demo/Demo.js');?>
        <!-- END JAVASCRIPT -->

        <?php echo $this->fetch('script') ?>
        <?php echo $this->fetch('scriptBottom')?>

    </body>
</html>