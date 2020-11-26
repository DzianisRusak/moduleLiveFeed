<?
//Module Sattings

use \Bitrix\Main\UI\Extension;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");
Extension::load("ui.vue");

global $APPLICATION;
$APPLICATION->SetTitle(GetMessage("OPTIONS_TITLE"));
?>
    <script type="text/x-template" id="tab-content">
        <div>
            <table>
                <thead>
                </thead>
                <tbody>
                <tr v-for="(option,index) in tab.tab_options">
                    <slot v-if="option.type === 'line'">
                        <td v-if="option.sort === 'head'" colspan="2" class="heading">
                            {{option.name}}
                        </td>
                    </slot>
                    <slot v-else>
                        <td class="adm-detail-content-cell-l">
                            {{option.name}}:
                        </td>
                        <td class="adm-detail-content-cell-r">
                            <input v-if="option.type==='input'" v-model="option.value" class="adm-input" size="86">
                            <input v-if="option.type==='input_n_save'" v-model="option.value" class="adm-input" size="86" action="not_save">
                            <textarea v-if="option.type==='textarea_n_save'"
                                      type="textarea"
                                      v-model="option.value"
                                      class="adm-input"
                                      style="width: 519px; height: 93px;"
                                      action="not_save">
                                </textarea>
                            <textarea v-if="option.type==='textarea'"
                                      type="textarea"
                                      v-model="option.value"
                                      class="adm-input"
                                      style="width: 519px; height: 93px;">
                                </textarea>
                            <input v-if="option.type==='checkbox_n_save'" type="checkbox" :name="index" v-model="option.value" action="not_save">
                            <input v-if="option.type==='checkbox'" type="checkbox" :name="index" v-model="option.value">
                            <select v-if="option.type==='list'" v-model="option.value">
                                <option v-for="val in option.list">{{val}}</option>
                            </select>
                            <select v-if="option.type==='list_n_save'" v-model="option.value" action="not_save">
                                <option v-for="val in option.list">{{val}}</option>
                            </select>
                            <select v-if="option.type==='function_list_n_save'" @change="$parent.$emit('onChangeSelectOption',index)" v-model="option.value" >
                                <option v-for="val in option.list">{{val}}</option>
                            </select>
                        </td>
                    </slot>
                </tr>
                </tbody>
            </table>
        </div>

    </script>
    <div id="app">
        <div id="tabControl_tabs" style="left: 0px;" class="adm-detail-tabs-block">
            <span  v-for="tab in tabs"
                   :class="{'is-active': tab.active,'adm-detail-tab-active':tab.active}"
                   class="adm-detail-tab  adm-detail-tab-last"
                   @click="setActive(tab)"
            >
                {{tab.tab_name}}
            </span>
        </div>
        <div class="adm-detail-content-wrap">
            <div class="adm-detail-content">
                <div class="adm-detail-title">{{StateOption}}</div>
                <div class="adm-detail-content-item-block">
                    <tab-content class="content" :tab="currentTab"/>
                </div>
                <div class="adm-detail-content-btns-wrap" id="tabControl_buttons_div" style="left: 0px;">
                    <div class="adm-detail-content-btns">
                        <input
                                v-for="(button,index) in currentTab.buttons"
                                type="button"
                                :value="button.button_name"
                                @click="LoadFunction(index)"
                                :class="button.class"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       let data=JSON.parse('<?=\Phpdev\Mylivefeed\Settings\AdminSettings::getSettings()?>');
       console.log(data);
       BX.Vue.component('tab-content', {
           template: '#tab-content',
           props: ['tab']
       });
       BX.Vue.create({
           el: '#app',
           data: {
               tabs:data.tabs,
               StateOption:'',
               Response:{}
           },
           methods: {
               setActive(tab) {
                   this.tabs.forEach(el => {
                       el.active = el === tab;
                   })
               },
               LoadFunction(index){
                   eval('this.'+this.currentTab.buttons[index].button_function+'(this.tabs)');
               },
               SaveOptions(data){
                   var request = BX.ajax.runAction('phpdev:mylivefeed.api.AdminController.SaveOptions', {
                       data: {
                           data: this.currentTab
                       }
                   });
                    _self=this;
                   request.then(function(response){
                       _self.StateOption = response.data.state;
                       console.log(response);
                   });
               },
               UpdateOptions(data){
                   console.log('UpdateOptions')
               }
           },
           computed: {
               currentTab: function () {
                   return this.tabs.reduce((accum, curr) => {return curr.active ? curr : accum}, {});
               }
           }
       });
    </script>
<?require_once ($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");?>