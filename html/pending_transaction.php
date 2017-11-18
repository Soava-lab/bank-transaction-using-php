    <br>
    <div class="row">
        <div class="col-xs-10 col-md-12">
            <div class="panel panel-primary" ng-controller="fund_transfer" ng-cloak>
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h2 class="panel-title">
                       Pending Transactions
                    </h2>
                </div>
                    <ul class="list-group">
                    <li class="list-group-item">
                    <?php if(count($user->transactions) > 0){ ?>
                    <div class="alert" ng-class="transfer_msg_class" ng-if="transfer_msg">{{transfer_msg}}</div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Transaction date</th>
                                <th>Remitter</th>
                                <th>Beneficiary</th>
                                <th>Transfer Amount</th>
                                <th>Description</th>
                                <th>Action</th>    
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($user->transactions as $trans){?>
                            <tr class="ref_<?=$trans->ref_id?>">
                                <td><?=$trans->ref_date?></td>
                                <td><?=ucfirst($trans->username)?></td>
                                <td><?=ucfirst($trans->benificiary)?></td>
                                <td><?=Request::label('rs').$trans->amount?></td>
                                <td><?=$trans->note?></td>
                                <td><a href="javascript:;" ng-click="approve_transaction(<?=$trans->ref_id?>);">Approve</a></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    <?php }else{?>
                     <div class="alert alert-info">No transactions found</div>
                    <?php }?>
                    </li>

                </ul>
            </div>
        </div>
    </div>