    <br>
    <div class="row">
        <div class="col-xs-10 col-md-12">
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h2 class="panel-title">
                        Transactions
                    </h2>
                </div>
                    <ul class="list-group">
                    
                    <li class="list-group-item">
                    <?php if(count($user->transactions) > 0){ ?>
                    <h4 class="pull-left">Account No : <?=$user->account_id?></h4>
                    <h4 class="pull-right"><a href="./export-transaction" class="btn btn-warning">Export</a></h4> <div class="clearfix">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Transaction date</th>
                                <th>Remitter</th>
                                <th>Beneficiary</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Amount</th>
                                <th>Description</th>    
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($user->transactions as $trans){?>
                            <tr>
                                <td><?=$trans->txn_date?></td>
                                <td><?=ucfirst($trans->username)?></td>
                                <td><?=ucfirst($trans->benificiary)?></td>
                                <td><?=($trans->txn_type=="Debit")?Request::label('rs').$trans->txn_amount:'-'?></td>
                                <td><?=($trans->txn_type=="Credit")?Request::label('rs').$trans->txn_amount:'-'?></td>
                                <td><?=Request::label('rs').$trans->current_balance?></td>
                                <td><?=$trans->txn_note?></td>
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