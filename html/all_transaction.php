    <br>
    <div class="row">
        <div class="col-xs-10 col-md-12">
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h2 class="panel-title">
                       All Transactions
                    </h2>
                </div>
                    <ul class="list-group">                    
                    <li class="list-group-item">
                    <?php if(count($user->transactions) > 0){ ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Transaction date</th>
                                <th>Remitter</th>
                                <th>Beneficiary</th>
                                <th>Transfer Amount</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($user->transactions as $trans){?>
                            <tr>
                                <td><?=$trans->ref_date?></td>
                                <td><?=ucfirst($trans->username)?></td>
                                <td><?=ucfirst($trans->benificiary)?></td>
                                <td><?=Request::label('rs').$trans->amount?></td>
                                <td><?=$trans->note?></td>
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