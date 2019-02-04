<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-users fa-fw"></i> <?= __('Users') ?>
        <div class="pull-right">
            <?php echo $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']).' '.$this->Html->tag('i', '', ['class' => 'fa fa-user']), ['controller' => 'Users', 'action' => 'add'], ['escape' => false, 'class' => 'btn btn-success btn-xs', 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'title' => __('Add a new user')]);?>
        </div>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table cellpadding="0" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('last_name', __('Last Name')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('password_changed', __('Password Changed')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('active', __('Status')) ?></th>
                                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $this->Number->format($user->id) ?></td>
                                <td><?= h($user->name) ?></td>
                                <td><?= h($user->last_name) ?></td>
                                <td><?= h($user->email) ?></td>
                                <td><?= ($user->password_changed == 1) ? __('Yes') : __('No') ?></td>
                                <td><?= ($user->active == 1) ? __('Enabled') : __('Disabled') ?></td>
                                <td><?= h($user->created) ?></td>
                                <td><?= h($user->modified) ?></td>
                                <td class="actions">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                            <?php echo __('Actions');?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                                            </li>
                                            <li>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                                            </li>
                                            <li>
                                                <?php if($user->active == 1):?>
                                                    <?= $this->Form->postLink(__('Disable'), ['action' => 'disable', $user->id], ['confirm' => __('Are you sure you want to disable # {0}?', $user->name)]) ?>
                                                <?php else:?>
                                                    <?= $this->Form->postLink(__('Enable'), ['action' => 'active', $user->id], ['confirm' => __('Are you sure you want to active # {0}?', $user->name)]) ?>
                                                <?php endif;?>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete {0}?', $user->id)]) ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="paginator">
                        <ul class="pagination">
                            <?= $this->Paginator->first('<< ' . __('First')) ?>
                            <?= $this->Paginator->prev('< ' . __('Previous')) ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next(__('Next') . ' >') ?>
                            <?= $this->Paginator->last(__('Last') . ' >>') ?>
                        </ul>
                        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>