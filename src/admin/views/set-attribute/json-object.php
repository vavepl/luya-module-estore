<div class="form-group form-side-by-side" style="width: 90%;" ng-class="{'input--hide-label': i18n}">
    <div class="form-side form-side-label">
        <label>{{label}}</label>
    </div>
    <div class="form-side">
        <div class="list zaa-json-array">
            <div ng-repeat="(key,value) in model" class="list-item">
                <div ng-if="helper[key]" class="my-2">
                    <zaa-color model="model[key]"></zaa-color>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">{{key}}</div>
                    </div>
                    <input class="form-control" type="text" ng-model="model[key]" />
                </div>
                <div class="list-buttons">
                    <div class="btn-group" role="group">
                        <button ng-click="toggleWindow(key)" type="button" class="btn btn-info btn-icon"><i class="material-icons">help</i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger" ng-click="remove(key)"><i class="material-icons">remove</i></button>
                    </div>
                </div>
            </div>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="{{i18n['js_jsonobject_newkey']}}" aria-label="{{i18n['js_jsonobject_newkey']}}" ng-model="newKey">
                <div class="input-group-append">
                    <button class="btn btn-sm btn-success" type="button" ng-click="add(newKey);newKey=null;"><i class="material-icons">add</i></button>
                </div>
            </div>
        </div>
    </div>
</div>
