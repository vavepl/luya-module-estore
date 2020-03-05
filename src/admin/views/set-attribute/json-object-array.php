<div class="form-group form-side-by-side" ng-class="{'input--hide-label': i18n}">
    <div class="form-side form-side-label">
        <label>{{label}}</label>
    </div>
    <div class="form-side">
        <div class="list zaa-file-array-upload">
            <p class="alert alert-info" ng-hide="model.length > 0"> {{ i18n['js_dir_no_selection'] }} </p>
            <div ng-repeat="(key,row) in model track by key" class="list-item">
                <estore-json-object model="row" label="Key Value Input" />
                <div class="list-buttons" style="right: 50px;">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-info" ng-click="moveUp(key)" ng-if="key > 0"><i class="material-icons">keyboard_arrow_up</i></button>
                        <button type="button" class="btn btn-sm btn-outline-info" ng-click="moveDown(key)" ng-if="showDownButton(key)"><i class="material-icons">keyboard_arrow_down</i></button>
                        <button type="button" class="btn btn-sm btn-outline-danger" ng-click="remove(key)"><i class="material-icons">remove</i></button>
                    </div>
                </div>
            </div>
            <button ng-click="add()" type="button" class="btn btn-sm btn-success list-add-button"><i class="material-icons">add</i></button>
        </div>
    </div>
</div>