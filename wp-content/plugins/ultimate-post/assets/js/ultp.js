(function($) {
    'use strict';
    // *************************************
    // Social Share window
    // *************************************
    $(".ultp-post-share-item a").each(function() {
        $(this).click(function() {
            // For Share window opening
            let share_url = $(this).attr("url");
            let width = 800;
            let height = 500;
            let leftPosition, topPosition;
            //Allow for borders.
            leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
            //Allow for title and status bars.
            topPosition = (window.screen.height / 2) - ((height / 2) + 50);
            let windowFeatures = "height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition;
            window.open(share_url,'sharer', windowFeatures);
            // For Share count add
            let id = $(this).parents(".ultp-post-share-item-inner-block").attr("postId");
            let count = $(this).parents(".ultp-post-share-item-inner-block").attr("count");
            $.ajax({
                url: ultp_data_frontend.ajax,
                type: 'POST',
                data: {
                    action: 'ultp_share_count', 
                    shareCount:count, 
                    postId: id,
                    wpnonce: ultp_data_frontend.security
                },
                error: function(xhr) {
                    console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                },
            });
            
            return false;
        })
    })
    // *************************************
    // News Ticker
    // *************************************
    $('.ultp-news-ticker').each( function () {
        $(this).UltpSlider({
            type: $(this).data('type'),
            direction: $(this).data('direction'),
            speed: $(this).data('speed'),
            pauseOnHover: $(this).data('hover') == 1 ? true : false,
            controls: {
                prev: $(this).closest('.ultp-newsTicker-wrap').find('.ultp-news-ticker-prev'),
                next: $(this).closest('.ultp-newsTicker-wrap').find('.ultp-news-ticker-next'),
                toggle: $(this).closest('.ultp-newsTicker-wrap').find('.ultp-news-ticker-pause')
            }
        });
    });

    // *************************************
    // Table of Contents
    // *************************************
    $(".ultp-toc-backtotop").click(function(e) {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
    
    $(window).scroll(function() {
        scrollTopButton(); 
    });

    function scrollTopButton() {
        if ($(document).scrollTop() > 1000) {
            $('.ultp-toc-backtotop').addClass('tocshow');
            $('.wp-block-ultimate-post-table-of-content').addClass('ultp-toc-scroll');
        } else {
            $('.ultp-toc-backtotop').removeClass('tocshow');
            $('.wp-block-ultimate-post-table-of-content').removeClass('ultp-toc-scroll');
        }
    }
    scrollTopButton();

    $(".ultp-collapsible-open").click(function(e) {
        $('.ultp-collapsible-toggle').removeClass('ultp-toggle-collapsed');
        $('.ultp-block-toc-body').show();
    });

    $(".ultp-collapsible-hide").click(function(e) {
        $('.ultp-collapsible-toggle').addClass('ultp-toggle-collapsed');
        $('.ultp-block-toc-body').hide();
    });
    
    $(".ultp-toc-lists li a").click(function() {
        $([document.documentElement, document.body]).animate({
            scrollTop: $($(this).attr('href')).offset().top - 50
        }, 500);
    });  


    // *************************************
    // Flex Menu
    // *************************************
    $(document).ready(function() {
        if ($('.ultp-flex-menu').length > 0) {
            const menuText = $('ul.ultp-flex-menu').data('name');
            $('ul.ultp-flex-menu').flexMenu({linkText: menuText, linkTextAll: menuText, linkTitle: menuText});
        }
    });
    $(document).on("click", function (e) {
        if ($(e.target).closest(".flexMenu-viewMore").length === 0) {
            $('.flexMenu-viewMore').removeClass('active');
            $('.flexMenu-viewMore').children("ul.flexMenu-popup").css("display","none")
        }
    });
    $(document).on('click', '.ultp-filter-navigation .flexMenu-popup .filter-item', function(e){
        $('.flexMenu-viewMore').removeClass('active');
        $('.flexMenu-viewMore').children("ul.flexMenu-popup").css("display","none")
    });
      

    // *************************************
    // Previous Next
    // *************************************
    $('.ultp-prev-action, .ultp-next-action').off().on('click', function(e) {
        e.preventDefault();

        let parents = $(this).closest('.ultp-next-prev-wrap'),
            wrap    = parents.closest('.ultp-block-wrapper').find('.ultp-block-items-wrap'),
            paged   = parseInt(parents.data('pagenum')),
            pages   = parseInt(parents.data('pages'));
        
        if ($(this).hasClass('ultp-prev-action')) {
            if ($(this).hasClass('ultp-disable')) {
                return
            }else{
                paged--;
                parents.data('pagenum', paged);
                parents.find('.ultp-prev-action, .ultp-next-action').removeClass('ultp-disable')
                if (paged == 1) {
                    $(this).addClass('ultp-disable');
                }
            }
        }
        if ($(this).hasClass('ultp-next-action')) {
            if ($(this).hasClass('ultp-disable')) {
                return
            }else{
                paged++;
                parents.data('pagenum', paged);
                parents.find('.ultp-prev-action, .ultp-next-action').removeClass('ultp-disable')
                if (paged == pages) {
                    $(this).addClass('ultp-disable');
                }
            }
        }

        let post_ID = (parents.parents('.ultp-shortcode').length != 0) ? parents.parents('.ultp-shortcode').data('postid') : parents.data('postid');

        if ($(this).closest('.ultp-builder-content').length > 0) {
            post_ID = $(this).closest('.ultp-builder-content').data('postid')
        }

		$.ajax({
			url: ultp_data_frontend.ajax,
            type: 'POST',
            data: {
                action: 'ultp_next_prev', 
                paged: paged ,
                blockId: parents.data('blockid'),
                postId: post_ID,
                blockName: parents.data('blockname'),
                builder: parents.data('builder'),
                wpnonce: ultp_data_frontend.security
            },
            beforeSend: function() {
                parents.closest('.ultp-block-wrapper').addClass('ultp-loading-active')
            },
            success: function(data) {
                wrap.html(data);
            },
            complete:function() {
                parents.closest('.ultp-block-wrapper').removeClass('ultp-loading-active');
            },
            error: function(xhr) {
                console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                parents.closest('.ultp-block-wrapper').removeClass('ultp-loading-active');
            },
        });
    });
       

    // *************************************
    // Loadmore Append
    // *************************************
    $('.ultp-loadmore-action').off().on('click', function(e) {
        e.preventDefault();

        let that    = $(this),
            parents = that.closest('.ultp-block-wrapper'),
            paged   = parseInt(that.data('pagenum')),
            pages   = parseInt(that.data('pages'));
        
        if (that.hasClass('ultp-disable')) {
            return
        }else{
            paged++;
            that.data('pagenum', paged);
            if (paged == pages) {
                $(this).addClass('ultp-disable');
            }else{
                $(this).removeClass('ultp-disable');
            }
        }

        let post_ID = (that.parents('.ultp-shortcode').length != 0) ? that.parents('.ultp-shortcode').data('postid') : that.data('postid');

        if (that.closest('.ultp-builder-content').length > 0) {
            post_ID = that.closest('.ultp-builder-content').data('postid')
        }

        $.ajax({
            url: ultp_data_frontend.ajax,
            type: 'POST',
            data: {
                action: 'ultp_next_prev', 
                paged: paged ,
                blockId: that.data('blockid'),
                postId: post_ID,
                blockName: that.data('blockname'),
                builder: that.data('builder'),
                wpnonce: ultp_data_frontend.security
            },
            beforeSend: function() {
                parents.addClass('ultp-loading-active');
            },
            success: function(data) {
                $(data).insertBefore( parents.find('.ultp-loadmore-insert-before') );
            },
            complete:function() {
                parents.removeClass('ultp-loading-active');
            },
            error: function(xhr) {
                console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                parents.removeClass('ultp-loading-active');
            },
        });
    });


    // *************************************
    // Filter
    // *************************************
    $(document).on('click', '.ultp-filter-wrap li a', function(e) {
        e.preventDefault();

        if ($(this).closest('li').hasClass('filter-item')) {
            let that    = $(this),
                parents = that.closest('.ultp-filter-wrap'),
                wrap = that.closest('.ultp-block-wrapper');

                parents.find('a').removeClass('filter-active');
                that.addClass('filter-active');

            let post_ID = (parents.parents('.ultp-shortcode').length != 0) ? parents.parents('.ultp-shortcode').data('postid') : parents.data('postid');

            if (that.closest('.ultp-builder-content').length > 0) {
                post_ID = that.closest('.ultp-builder-content').data('postid')
            }

            if (parents.data('blockid')) {
                $.ajax({
                    url: ultp_data_frontend.ajax,
                    type: 'POST',
                    data: {
                        action: 'ultp_filter', 
                        taxtype: parents.data('taxtype'),
                        taxonomy: that.data('taxonomy'),
                        blockId: parents.data('blockid'),
                        postId: post_ID,
                        blockName: parents.data('blockname'),
                        wpnonce: ultp_data_frontend.security
                    },
                    beforeSend: function() {
                        wrap.addClass('ultp-loading-active');
                    },
                    success: function(data) {
                        wrap.find('.ultp-block-items-wrap').html(data);
                    },
                    complete:function() {
                        wrap.removeClass('ultp-loading-active');
                    },
                    error: function(xhr) {
                        console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                        wrap.removeClass('ultp-loading-active');
                    },
                });
            }
        }
    });


    // *************************************
    // Pagination Number
    // *************************************
    function showHide(parents, pageNum, pages) {
        if (pageNum == 1) {
            parents.find('.ultp-prev-page-numbers').hide()
            parents.find('.ultp-next-page-numbers').show()
        } else if (pageNum == pages) {
            parents.find('.ultp-prev-page-numbers').show()
            parents.find('.ultp-next-page-numbers').hide()
        } else {
            parents.find('.ultp-prev-page-numbers').show()
            parents.find('.ultp-next-page-numbers').show()
        }


        if (pageNum > 2) {
            parents.find('.ultp-first-pages').show()
            parents.find('.ultp-first-dot').show()
        }else{
            parents.find('.ultp-first-pages').hide()
            parents.find('.ultp-first-dot').hide()
        }
        
        if (pages > pageNum + 1) {
            parents.find('.ultp-last-pages').show()
            parents.find('.ultp-last-dot').show()
        }else{
            parents.find('.ultp-last-pages').hide()
            parents.find('.ultp-last-dot').hide()
        }
    }

    function serial(parents, pageNum, pages) {
        let datas = pageNum <= 2 ? [1,2,3] : ( pages == pageNum ? [pages-2,pages-1, pages] : [pageNum-1,pageNum,pageNum+1] )
        let i = 0
        parents.find('.ultp-center-item').each(function() {
            if (pageNum == datas[i]) {
                $(this).addClass('pagination-active')
            }
            $(this).find('a').blur();
            $(this).attr('data-current', datas[i]).find('a').text(datas[i])
            i++
        });
    }

    $('.ultp-pagination-ajax-action li').off().on('click', function(e) {
        e.preventDefault();

        let that    = $(this),
            parents = that.closest('.ultp-pagination-ajax-action'),
            wrap = that.closest('.ultp-block-wrapper');

        let pageNum = 1;
        let pages = parents.attr('data-pages');
        
        if (that.attr('data-current')) {
            pageNum = Number(that.attr('data-current'))
            parents.attr('data-paged', pageNum).find('li').removeClass('pagination-active')
            serial(parents, pageNum, pages)
            showHide(parents, pageNum, pages)
        } else {
            if (that.hasClass('ultp-prev-page-numbers')) {
                pageNum = Number(parents.attr('data-paged')) - 1
                parents.attr('data-paged', pageNum).find('li').removeClass('pagination-active')
                //parents.find('li[data-current="'+pageNum+'"]').addClass('pagination-active')
                serial(parents, pageNum, pages)
                showHide(parents, pageNum, pages)
            } else if (that.hasClass('ultp-next-page-numbers')) {
                pageNum = Number(parents.attr('data-paged')) + 1
                parents.attr('data-paged', pageNum).find('li').removeClass('pagination-active')
                //parents.find('li[data-current="'+pageNum+'"]').addClass('pagination-active')
                serial(parents, pageNum, pages)
                showHide(parents, pageNum, pages)
            }
        }

        let post_ID = (parents.parents('.ultp-shortcode').length != 0) ? parents.parents('.ultp-shortcode').data('postid') : parents.data('postid');

        if (that.closest('.ultp-builder-content').length > 0) {
            post_ID = that.closest('.ultp-builder-content').data('postid')
        }

        if (pageNum) {
            $.ajax({
                url: ultp_data_frontend.ajax,
                type: 'POST',
                data: {
                    action: 'ultp_pagination', 
                    paged: pageNum,
                    blockId: parents.data('blockid'),
                    postId: post_ID,
                    blockName: parents.data('blockname'),
                    builder: parents.data('builder'),
                    wpnonce: ultp_data_frontend.security
                },
                beforeSend: function() {
                    wrap.addClass('ultp-loading-active');
                },
                success: function(data) {
                    wrap.find('.ultp-block-items-wrap').html(data);
                    if ($(window).scrollTop() > wrap.offset().top) {
                        $([document.documentElement, document.body]).animate({
                            scrollTop: wrap.offset().top - 50
                        }, 100);
                    }
                },
                complete:function() {
                    wrap.removeClass('ultp-loading-active');
                },
                error: function(xhr) {
                    console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
                    wrap.removeClass('ultp-loading-active');
                },
            });
        }
    });
    
    // *************************************
    // SlideShow
    // *************************************
    
    // Slideshow Display For Elementor via Shortcode
    $( window ).on( 'elementor/frontend/init', () => {
        setTimeout( () => {
            if ($('.elementor-editor-active').length > 0) {
                slideshowDisplay();
            }
        }, 2000);
    });
    
    function slideshowDisplay() {
        $('.wp-block-ultimate-post-post-slider-1, .wp-block-ultimate-post-post-slider-2').each(function () {
            const sectionId = '#' + $(this).attr('id');
            let selector = $(sectionId).find('.ultp-block-items-wrap');
            if($(this).parent('.ultp-shortcode')) {
                selector = $(this).find('.ultp-block-items-wrap');
            }
            let settings = {
                arrows: true,
                dots: selector.data('dots') ? true : false,
                infinite: true,
                speed: 500,
                slidesToShow: selector.data('slidelg') || 1,
                slidesToScroll: 1,
                autoplay: selector.data('autoplay') ? true : false,
                autoplaySpeed: selector.data('slidespeed') || 3000,
                cssEase: "linear",
                prevArrow: selector.parent().find('.ultp-slick-prev').html(),
                nextArrow: selector.parent().find('.ultp-slick-next').html(),
            };
            
            let layTemp = selector.data('layout') == "slide2" || selector.data('layout') == "slide3"  || selector.data('layout') == "slide5" || selector.data('layout') == "slide6"  || selector.data('layout') == "slide8" ;

            if(!selector.data('layout')) { // Slider 1
                if (selector.data('slidelg') < 2) {
                    settings.fade = selector.data('fade') ? true : false
                } else {
                    settings.responsive = [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: selector.data('slidesm') || 1,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: selector.data('slidexs') || 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                }
            } else { // Slider 2
                if( selector.data('fade') && layTemp) {
                    settings.fade = selector.data('fade') ? true : false;
                } else if ( !(selector.data('fade')) && layTemp) {
                    settings.slidesToShow = selector.data('slidelg') || 1,
                    settings.responsive = [
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: selector.data('slidesm') || 1,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: selector.data('slidexs') || 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                }  else {
                        settings.slidesToShow = selector.data('slidelg') || 1,
                        settings.centerMode =  true;
                        settings.centerPadding = `${selector.data('paddlg')}px` || 100
                        settings.responsive = [
                            {
                                breakpoint: 991,
                                settings: {
                                    slidesToShow: selector.data('slidesm') || 1,
                                    slidesToScroll: 1,
                                    centerPadding: `${selector.data('paddsm')}px` || 50,
                                }
                            },
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesToShow: selector.data('slidexs') || 1,
                                    slidesToScroll: 1,
                                    centerPadding: `${selector.data('paddxs')}px` || 50,
                                }
                            }
                        ]
                }
            }
            selector.not('.slick-initialized').slick(settings);
        });
    }
    slideshowDisplay();

    // *************************************
    // Accessibility for Loadmore Added
    // *************************************
    $('span[role="button"]').on('keydown', function (e) {
        const keyD = e.key !== undefined ? e.key : e.keyCode;
        if ((keyD === 'Enter' || keyD === 13) || (['Spacebar', ' '].indexOf(keyD) >= 0 || keyD === 32)) {
            e.preventDefault();
            this.click();
        }
    });

})( jQuery );