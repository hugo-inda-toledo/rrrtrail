<div class="row">
    <div class="col-lg-12">
        <h1>
            <?php echo __('Companies');?> <small><?php echo __('List all companies');?></small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <?php echo $this->Html->link($this->Html->tag('span', '', ['class' => 'fa fa-dashboard']).' '.__('Dashboard'), ['controller' => 'dashboard', 'action' => 'index', 'plugin' => 'management'], ['escape' => false]);?>
            </li>
            <li class="active">
                <i class="icon-file-alt"></i> <?php echo __('Companies');?>
            </li>
        </ol>
    </div>
</div>

<?php if(count($companies) > 0):?>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover tablesorter">
                    <thead>
                        <tr>
                            <th><?php echo __('Company');?> <i class="fa fa-sort"></i></th>
                            <th><?php echo __('Status');?><i class="fa fa-sort"></i></th>
                            <th><?php echo __('Actions');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($companies as $company): ?>
                            <tr>
                                <td>
                                    <?php 
                                        echo $this->Html->link($this->Html->image('companies/'.$company->company_logo, ['class' => 'pull-left img-responsive', 'style' => 'width: 40px;']).' '.$company->company_name, ['controller' => 'companies', 'action' => 'view', $company->id, 'plugin' => 'management'], ['escape' => false, 'style' => '    margin: 10px;font-size: 20px;']);
                                    ?>
                                </td>
                                <td>
                                    <?php if($company->active == 1):?>
                                        <span class="label label-success"><?php echo __('Enabled');?></span>
                                    <?php else:?>
                                        <span class="label label-danger"><?php echo __('Disabled');?></span>
                                    <?php endif;?>
                                </td>
                                <td>32.3%</td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <div class="paginator">
                    <ul class="pagination">
                        <?= $this->Paginator->first('<< ' . __('first')) ?>
                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('next') . ' >') ?>
                        <?= $this->Paginator->last(__('last') . ' >>') ?>
                    </ul>
                    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                </div>
            </div>

        </div>
    </div>
<?php endif;?>