(function($) {
    'use strict';

    $(".ultp-shortcode-copy").click(function(e) {
        e.preventDefault();
        const that = $(this);
        navigator.clipboard.writeText(that.text());
        that.append("<span>Copied!</span>");
        setTimeout( function() { that.find('span').remove(); }, 500 );
    });

    $('.ultp-color-picker').wpColorPicker({
        change: function(e, ui){
            $(this).closest('.ultp-settings-field').find('.ultp-color-code').val(e.target.value)
        }
    });
    $('.ultp-color-code').bind("change keyup input",function() {
       $(this).closest('.ultp-settings-field').find('.wp-color-result').css("background-color", $(this).val())
    }); 

    // *************************************
    // Add target blank for upgrade button
    // *************************************
    $('.toplevel_page_ultp-settings ul > li > a').each(function (e) {
        if ($(this).attr('href')) {
            if($(this).attr('href').indexOf("?ultp=plugins") > 0) {
                $(this).attr('target', '_blank');
            }
        }
    });

    $(document).on('click', '.ultp-settings-content .ultp-addons-enable', function(e) {
        const that = this
        const val = $(that).hasClass('ultp-blocks') ? (this.checked ? 'yes' : '') : this.checked
        
        if ($(that).hasClass('disabled')) {
            e.preventDefault();
            $('.ultp-dashboard-container .ultp-popup-container').addClass('active');
        } else {
            $.ajax({
                url: ultp_option_panel.ajax,
                type: 'POST',
                data: {
                    action: 'ultp_addon', 
                    addon: $(that).attr('id'),
                    value: $(that).data('type') == 'blocks' ? (val?'yes':'') : val,
                    wpnonce: ultp_option_panel.security
                },
                success: function(data) {
                    if( $(that).data('type') == 'ultp_templates' || $(that).data('type') == 'ultp_builder' ) {
                        location.reload();   
                    }
                },
                error: function(xhr) {
                    console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                },
            });
        }        
    });
     

    $(document).on('click', '.ultp-popup-close', function(e){
        if (!$(this).hasClass('popup-center')) {
            $(this).closest('.ultp-popup-container').removeClass('active');
        }
    });
    
    $(document).on('click', '.ultp-block-settings', function(e){
        $(this).parent().find('.ultp-popup-container').addClass('active');
    });

    $(document).on('click', '.ultp-addons-setting-save, .ultp-submit-button button', function(e){
        e.preventDefault()
		const that = $(this).closest('form')
        let filterFormData = {};
        const formData = that.serializeArray();
        for (let i in formData) {
            const field = formData[i];
            if (field['name'].indexOf('[]') > 0) {
                const key = field['name'].replace('[]', '');
                if (filterFormData[key]) {
                    filterFormData[key].push(field['value']);
                } else {
                    filterFormData[key] = [field['value']];
                }
            } else {
                filterFormData[field['name']] = field['value'];
            }
        }

        // Empty Radio or Checkbox Select
        const $radio = that.find('input[type=radio],input[type=checkbox]',this);
        if ($radio.length > 0) {
            $.each($radio,function(){
                if(!filterFormData.hasOwnProperty(this.name)){
                    filterFormData[this.name] = '';
                }
            });
        }

        $.ajax({
            url: ultp_option_panel.ajax,
            type: 'POST',
            data: {
                action: 'plugin_settings', 
                data: filterFormData,
                wpnonce: ultp_option_panel.security
            },
            success: function(data) {
				if (data.success) {
					that.find('.ultp-data-message').html(data.data).fadeIn();
					setTimeout(function() { that.find(".ultp-data-message").fadeOut(); }, 3000);
				}
            },
            error: function(xhr) {
                console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
            },
        });

    });
    

    const actionBtn = $('.page-title-action');
    const savedBtn = $(".ultp-saved-templates-action");
    if (savedBtn.length > 0 ) {
        if (savedBtn.data())
        actionBtn.addClass('ultp-save-templates-pro').text( savedBtn.data('text') )
        actionBtn.attr( 'href', savedBtn.data('link') )
        actionBtn.attr( 'target', '_blank' )
    }

    // *************************************
    // Add URL for PostX
    // *************************************
    $(document).on('click', '#plugin-information-content ul > li > a', function(e) {
        const URL = $(this).attr('href');
        if (URL.includes('downloads/gutenberg-post-blocks-pro')) {
            e.preventDefault();
            window.open("https://www.wpxpo.com/postx/");
        }
    });

    // *************************************
    // PostX Builder Metabox Settings
    // *************************************
    const selector = $('.postx-meta-sidebar-position select');
    function changeSidebar() {
        if (selector.length > 0) {
            if (selector.val() == 'left' || selector.val() == 'right') {
                $('.postx-meta-sidebar-widget').show();
            } else {
                $('.postx-meta-sidebar-widget').hide();
            }
        }
    }
    changeSidebar();
    selector.on('change', function() {changeSidebar()});

    // Settings Option
    if ('?page=ultp-settings' == window.location.search) {
        const hash = window.location.hash
        if (hash) {
            if (hash.indexOf('demoid') < 0) {
                $('ul.ultp-settings-tab > li, ul.ultp-settings-content > li, .toplevel_page_ultp-settings ul li').removeClass('current');
                $('ul.ultp-settings-tab > li a[href$='+hash+'], .toplevel_page_ultp-settings ul li a[href$='+hash+']').closest('li').addClass('current');
                $('ul.ultp-settings-content > li[data-tab='+hash+']').addClass('current');
                if (hash == '#home') {
                    $('.toplevel_page_ultp-settings ul li.wp-first-item').addClass('current');
                } else {
                    $('.toplevel_page_ultp-settings ul li a[href$='+hash+']').addClass('current');
                }
            }
        }
    }

    $(document).on('click', 'ul.ultp-settings-tab > li a, .toplevel_page_ultp-settings ul li a', function(e) {
        let value = $(this).attr('href')
        if (value) {
            value = value.split('#');
            if (typeof value[1] != 'undefined' && value[1].indexOf('demoid') < 0 && value[1]) {
                $('ul.ultp-settings-tab > li a, .toplevel_page_ultp-settings ul li a').closest('ul').find('li').removeClass('current');
                $('a[href$='+value[1]+']') .closest('li').addClass('current');
                $(this).closest('li').addClass('current');
                $('ul.ultp-settings-content > li').removeClass('current');
                $('ul.ultp-settings-content > li[data-tab=#'+value[1]+']').addClass('current');
                if (value[1] == 'home') {
                    $('.toplevel_page_ultp-settings ul li.wp-first-item').addClass('current');
                }
            }
        }
    });


    $('.page-title-action').on('click', function(e) {
        if ($('.ultp-pro-needed').length > 0) {
            const href = $(this).attr('href')
            if (href.indexOf('post_type=ultp_builder') > 0) {
                e.preventDefault();
                $('.ultp-popup-container').addClass('active');
            }
        }
    });
    $('.ultp-popup-close').on('click', function(e) {
       $(this).closest('.ultp-popup-container').removeClass('active')
    });

    // *************************************
    // Disable Google Font Action
    // *************************************
    $('input[name=disable_google_font]').on('change', function() {
        if ($(this).is(':checked')) {
            $('#postx-regenerate-css').addClass('active')
        } else {
            $('#postx-regenerate-css').removeClass('active')
        }
    });
    $(document).on('click', '#postx-regenerate-css', function(e) {
        const that = $(this)
        $.ajax({
            url: ultp_option_panel.ajax,
            type: 'POST',
            data: {
                action: 'disable_google_font',
                wpnonce: ultp_option_panel.security
            },
            beforeSend: function() {
                that.addClass('ultp-spinner');
            },
            success: function(res) {
				if (res.success) {
                    that.find('.ultp-text').text(res.data);
				}
            },
            complete:function() {
                that.removeClass('ultp-spinner');
            },
            error: function(xhr) {
                console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
            },
        });
    });
    
})( jQuery );