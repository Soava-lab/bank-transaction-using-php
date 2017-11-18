    <br>
    <div class="row">
        <div class="col-xs-10 col-md-12" ng-controller="fund_transfer" ng-cloak>
            <div class="panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h2 class="panel-title">
                        Fund Transfer
                    </h2>
                </div>
                    <ul class="list-group">
                    
                    <li class="list-group-item">
                    
                    <h4>Fill all the fields</h4>
                       <form  ng-app="myApp" name="myForm" ng-submit="fund_transfer_submit(myForm.$valid)" novalidate>
                            <div class="form-group">
                             <label for="amount">Enter Amount</label>
                              <input type="number" class="form-control" name="amount" ng-model="amount" ng-min="1" required>
                              <span style="color:red" ng-show="myForm.amount.$dirty && myForm.amount.$invalid">
                              <span ng-show="myForm.amount.$error.required">Amount is required.</span>
                              <span ng-show="myForm.amount.$error.min">Amount should be at least Rs 1</span>
                              </span>
                            </div>

                            <div class="form-group">
                               <label for="account_id">Choose Benificiary</label>
                              <select name="account_id" ng-model="account_id" class="form-control" required>
                                    <option value="">Choose benificiary</option>
                                    <?php foreach ($user as $obj) {?>
                                    <option value="<?=$obj->account_id?>"><?=$obj->username?></option>
                                    <?php }?>
                                </select>
                              <span style="color:red" ng-show="myForm.account_id.$dirty && myForm.account_id.$invalid">
                              <span ng-show="myForm.account_id.$error.required">Choose benificiary account.</span>
                              </span>
                            </div>  

                            <div class="form-group">
                               <label for="txt_type_id">Transaction Type</label>
                              <select name="txt_type_id" ng-model="txt_type_id" class="form-control" required>
                                    <option value="">Choose Type</option>
                                    <?php foreach ($transaction_types as $trans) {?>
                                    <option value="<?=$trans->txt_type_id?>"><?=$trans->type?></option>
                                    <?php }?>
                                </select>
                              <span style="color:red" ng-show="myForm.txt_type_id.$dirty && myForm.txt_type_id.$invalid">
                              <span ng-show="myForm.txt_type_id.$error.required">Choose Transaction type.</span>
                              </span>
                            </div>

                            <div class="form-group">
                             <label for="note">Notes</label>
                              <textarea class="form-control" name="note" ng-model="note" cols="30" rows="4"></textarea>
                              </span>
                            </div>

                            <p>
                              <button type="button" class="btn btn-default" onclick="window.history.back()" class="btn btn-default">Cancel</button> &nbsp; <input class="btn btn-success" type="submit"
                              ng-disabled="myForm.$invalid || myForm.amount.$dirty && myForm.amount.$invalid ||
                              myForm.account_id.$dirty && myForm.account_id.$invalid"> 
                              <a href="./transactions">Go to transactions</a>
                              <div class="alert" ng-class="transfer_msg_class" ng-if="transfer_msg">{{transfer_msg}}</div>
                            </p>

                        </form>

                    </li>

                </ul>
                <br class="clearfix">
                
            </div>
        </div>
    </div>