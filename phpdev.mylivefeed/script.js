BX.ready(
    function () {
        BX.addCustomEvent('OnBXHtmlEditorInit', showDataPicker);

        function showDataPicker() {
            if (BX('myDataPicker') === null) {
                let dapaPicker = '<input type="text" id="myDataPicker" value="" name="date" onclick="BX.calendar({node: this, field: this, bTime: true});">';

                let parrent = BX('blogPostForm');
                let form = parrent.querySelector('.feed-add-post-form');
                let div = document.createElement('div');
                div.innerHTML = dapaPicker;
                form.append(div);

                let sendButton = parrent.querySelector('#blog-submit-button-save');

                BX.bind(sendButton, 'click', function () {
                    if(BX('myDataPicker').value.length > 0){
                        BX('show_title').value = "Y";
                        BX('blog-title').style.display="block";
                        BX('blog-title').style.visibility="none";
                        BX('POST_TITLE').value = BX('POST_TITLE').value + '{' + BX('myDataPicker').value + '}';
                    }
                });
            }
        }
    }
);