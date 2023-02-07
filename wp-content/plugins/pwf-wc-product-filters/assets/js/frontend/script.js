var pwfIsResponsiveView = false;
var pwfGetJsUsedFuntion = '';
var pwfWooHooks         = pwfWooHooks || {}; // Extend Hooks if exists or create new Hooks object.
pwfWooHooks.filters     = pwfWooHooks.filters || {}; // Registered filters
/**
 * Add a new Filter callback to Hooks.filters
 *
 * @param tag The tag specified by apply_filters()
 * @param callback The callback function to call when apply_filters() is called
 * @param priority Priority of filter to apply. Default: 10 (like WordPress)
 */
 pwfWooHooks.add_filter = function( tag, callback, priority ) {

    if( typeof priority === "undefined" ) {
        priority = 10;
    }

    // If the tag doesn't exist, create it.
    pwfWooHooks.filters[ tag ] = pwfWooHooks.filters[ tag ] || [];
    pwfWooHooks.filters[ tag ].push( { priority: priority, callback: callback } );

}
/**
 * Remove a Filter callback from Hooks.filters
 *
 * Must be the exact same callback signature.
 * Warning: Anonymous functions can not be removed.
 * @param tag The tag specified by apply_filters()
 * @param callback The callback function to remove
 */
 pwfWooHooks.remove_filter = function( tag, callback ) {

    pwfWooHooks.filters[ tag ] = pwfWooHooks.filters[ tag ] || [];

    pwfWooHooks.filters[ tag ].forEach( function( filter, i ) {
        if( filter.callback === callback ) {
            pwfWooHooks.filters[ tag ].splice(i, 1);
        }
    } );
}
/**
 * Calls filters that are stored in Hooks.filters for a specific tag or return
 * original value if no filters exist.
 *
 * @param tag A registered tag in Hook.filters
 * @options Optional JavaScript object to pass to the callbacks
 */
pwfWooHooks.apply_filters = function( tag, value, options ) {

    var filters = [];

    if( typeof pwfWooHooks.filters[ tag ] !== "undefined" && pwfWooHooks.filters[ tag ].length > 0 ) {

        pwfWooHooks.filters[ tag ].forEach( function( hook ) {

            filters[ hook.priority ] = filters[ hook.priority ] || [];
            filters[ hook.priority ].push( hook.callback );
        } );

        filters.forEach( function( hooks ) {

            hooks.forEach( function( callback ) {
                value = callback( value, options );
            } );

        } );
    }

    return value;
}
pwfWooHooks.apply_filters();

(function( $ ) {
	"use strict";
	/**
	 * Start define a Global Variables
	 */
	var activeFilterItems    = {}; // activeFilters
	var filterAttributes     = {}; // orderby, page number, per_page
	var currentUrlQuery      = ''; // hash
	var currentPageURL       = ''; // Remove last slach;
	var currentfFilterItems  = {};
	var translatedText       = '';
	var oldActiveFilterItems = false;
	var resetButtonClicked   = false;
	var pwfIsURLHasSlash     = true;
	var getProductsOnly      = false; // if get page only we don't need filter HTML again
	var pwfPaginationType    = 'numbers';
	var nextPageString       = 'page'; // can be 'page' or 'product-page'
	var getLabels            = false;
	
	/**
	 * this varibales used inside pwfWooFilter.init()
	 */
	var pwfFilterSetting;
	var pwfFilterID;

	/**
	 * End of Global Variables
	 */


	/**
	 * Hold Global function
	 */
	var PWF = {
		isEmptyObj: function( obj ) {
			return Object.keys(obj).length === 0;
		},
		isEmptyStr: function( str ) {
			return '' === str;
		}
	};

	/**
	 * Used to switch between pretty and no pretty links
	 * 
	 * @since 1.5.7
	 */
	var pwfController = {
		isPretty: function() {
			return ( 'on' === pwfFilterSetting.pretty_urls ) ? true : false;
		},
		/**
		 * Determin pagination end with /page/2 or /prdouct-page=2
		 * @since version 1.3.8
		 */
		setNextPageString: function() {
			let pageEnd            = '';
			let paginationSelector = pwfFilterSetting.pagination_selector;
			if ( $(paginationSelector).find('a').length ) {
				let link = $(paginationSelector).find('a').first().attr('href');
				if ( undefined !== link ) {
					let pageNum    = link.match(/product-page=\d+/);
					if ( null !== pageNum ) {
						pageEnd = 'product-page';
					}
				}
			}

			if ( PWF.isEmptyStr(pageEnd) ) {
				let queryString = window.location.search;
				if ( ! PWF.isEmptyStr(queryString) ) {
					let urlParams  = new URLSearchParams(queryString);
					if ( urlParams.has('product-page') ) {
						pageEnd = 'product-page';
					}
				}
			}

			if ( ! PWF.isEmptyStr(pageEnd) ) {
				nextPageString = 'product-page';
			}
		},
		setCurrentPageURL: function() {
			noPrettyLinks.setCurrentPageURL();
		},
		dbQueryIntegrated: function () {
			/**
			 * Check if selected filter items are
			 * excute/integrated with DB Query on backend
			 */
			let shopIntegrated = pwffilterVariables.filter_integrated;
			if ( 'yes' === shopIntegrated ) {
				if ( ! PWF.isEmptyObj( pwffilterVariables.selected_items ) ) {
					pwfWooFilter.updateActiveFilterItemsInJS();
					pwfWooFilter.setOldActiveFilterItems('true');
					if ( ! pwfController.isPretty() ) {
						pwfWooFilter.UpdateChangeQueryString();
					}
				}
			} else {
				if ( ! pwfController.isPretty() ) {
					pwfWooFilter.checkIfPageURLHasActiveFilterItems();
				}
			}
		},
		getBrowserUrlQueryString: function( addPageURL = true, isRedirectAjax = false ) {
			return noPrettyLinks.getBrowserUrlQueryString( addPageURL, isRedirectAjax );
		},
		/**
		 * Update URL query string (hash) after doing ajax
		 */
		updateBrowserUrlQueryString: function() {
			noPrettyLinks.updateBrowserUrlQueryString();
		},
		updateBrowserHistory: function( pageURL ) {
			if ( history.pushState ) {
				//window.history.pushState( { path:pageURL }, '', pageURL ); // using browser history
				window.history.replaceState( { path:pageURL }, '', pageURL );
			}
		},
		getPageLinkURL: function( oldUrl ) {
			let urlHashQuery = currentUrlQuery;
			let pageURL   = currentPageURL;
			let newUrl    = '';
			let customurl = '';
			if ( 'product-page' === nextPageString ) {
				let pageNum = oldUrl.match(/\d+/);
				if ( null !== pageNum ) {
					if ( pwfController.isPretty() ) {
						customurl = 'page/' + parseInt( pageNum[0] ) + '/';
					} else {
						customurl = '?product-page=' + parseInt( pageNum[0] );
					}
					if ( urlHashQuery.startsWith('?') ) {
						urlHashQuery = '&' + urlHashQuery.substring(1);
					}
				}
			} else {
				customurl = oldUrl.substring(1);
				if ( false === pwfIsURLHasSlash ) {
					customurl = customurl.slice( 0, -1 );
				}
			}

			newUrl = pageURL + customurl + urlHashQuery;

			return newUrl;
		}
	};

	/**
	 * Hold All functions return to no pretty links
	 * when link. eg /?product-category=clothing
	 * @since 1.5.7
	 */
	 var noPrettyLinks = {
		setCurrentPageURL: function() {
			let regForPage = ( false === pwfIsURLHasSlash ) ? new RegExp("page/\\d+$") : new RegExp("page/\\d+/$");
			let regForNum  = new RegExp("\\d+");
			let pathName   = window.location.pathname;
			let PageURL    = '';
			let pageNum    = '';
			let urlHasPage = pathName.match(regForPage); // check url has /page/num/

			if ( null !== urlHasPage ) {
				/**
				 * inside this code you can keep /page/num/ if orgianl in the url 
				 * if there are active filter remove /page/num/ else this is orginal part from url and keetp it
				 */

				PageURL = pathName.split(regForPage)[0]; // remove /page/num/
				pageNum = parseInt( urlHasPage[0].match(regForNum)[0] );
				if ( pageNum ) {
					filterAttributes['page'] = pageNum;
				}			
			} else {
				PageURL = pathName;
			}

			currentPageURL = window.location.protocol + "//" + window.location.host + PageURL;
		},
		getBrowserUrlQueryString: function( addPageURL = true, isRedirectAjax = false ) {
			let hash = '';
			if ( ! PWF.isEmptyObj(activeFilterItems) ) {
				let selectedOptions = activeFilterItems;
				for ( let key_id in currentfFilterItems ) {
					let filterItem = currentfFilterItems[key_id];
					let key        = filterItem['url_key'];
					if ( selectedOptions.hasOwnProperty( key ) ) {
						let fieldType = selectedOptions[key]['fieldType']; 
						let values    = selectedOptions[key]['values'];
						let notices   = selectedOptions[key]['notices'];
						hash          =  ( ! PWF.isEmptyStr(hash) ) ? hash += '&' : '';
						
						switch( fieldType ) {
							case 'priceslider':
								if ( selectedOptions[key].hasOwnProperty('priceUrlKey') ) {
									let minKey = selectedOptions[key]['priceUrlKey']['minPrice'];
									let maxKey = selectedOptions[key]['priceUrlKey']['maxPrice'];
									hash += minKey + '=' + values[0] + '&' +  maxKey + '=' + values[1];
								} else {
									hash += key + '=' + values[0] + '-' + values[1];
								}
								break;
							case 'rangeslider':
								if ( selectedOptions[key].hasOwnProperty('rangeUrlKey') ) {
									let minKey = selectedOptions[key]['rangeUrlKey']['minValue'];
									let maxKey = selectedOptions[key]['rangeUrlKey']['maxValue'];
									hash += minKey + '=' + values[0] + '&' +  maxKey + '=' + values[1];
								} else {
									hash += key + '=' + values[0] + '-' + values[1];
								}
								break;
							case 'date':
								if ( selectedOptions[key].hasOwnProperty('dateUrlKey') ) {
									let after  = selectedOptions[key]['dateUrlKey']['after'];
									let before = selectedOptions[key]['dateUrlKey']['before'];
									hash += after + '&' +  before + '=' + values[1];
								}
								break;
							case 'search':
								if ( 's' === key && pwffilterVariables.hasOwnProperty('add_posttype') && 'true' === pwffilterVariables.add_posttype ) {
									hash += key + '=' +  notices[0]['slug'] + '&post_type=product';
								} else {
									hash += key + '=' +  notices[0]['slug'];
								}
								break;
							default:
								hash += key + '=';
								for ( let i = 0; i < notices.length; i++ ) {
									hash +=  notices[i]['slug'];
									if ( ( i + 1 ) < notices.length ) {
										hash += ','
									}
								}
								break;
						}
					}
				}
			}

			let queryString = window.location.search;
			if ( PWF.isEmptyStr(queryString) && ! PWF.isEmptyStr(hash) ) {
				hash = '?' + hash;
			} else {
				let urlParams = new URLSearchParams(queryString);
				for ( let key in currentfFilterItems ) {
					let filter = currentfFilterItems[key];
					if ( 'priceslider' === filter['item_type'] ) {
						if ( urlParams.has( filter['url_key_min_price'] ) ) {
							urlParams.delete( filter['url_key_min_price'] );
						}
						if ( urlParams.has( filter['url_key_max_price'] ) ) {
							urlParams.delete( filter['url_key_max_price'] );
						}
						if ( urlParams.has( filter['url_key'] ) ) {
							urlParams.delete( filter['url_key'] );
						}
					} else if ( 'rangeslider' === filter['item_type'] ) {
						if ( urlParams.has( filter['url_key_range_slider_min'] ) ) {
							urlParams.delete( filter['url_key_range_slider_min'] );
						}
						if ( urlParams.has( filter['url_key_range_slider_max'] ) ) {
							urlParams.delete( filter['url_key_range_slider_max'] );
						}
						if ( urlParams.has( filter['url_key'] ) ) {
							urlParams.delete( filter['url_key'] );
						}
					} else if ( 'date' === filter['item_type'] ) {
						if ( urlParams.has( filter['url_key_date_after'] ) ) {
							urlParams.delete( filter['url_key_date_after'] );
						}
						if ( urlParams.has( filter['url_key_date_before'] ) ) {
							urlParams.delete( filter['url_key_date_before'] );
						}

					} else if ( 'search' === filter['item_type'] && 's' === filter['url_key'] && pwffilterVariables.hasOwnProperty('add_posttype') && 'true' === pwffilterVariables.add_posttype) {
						urlParams.delete( filter['url_key'] );
						if ( urlParams.has('post_type') ) {
							urlParams.delete( 'post_type' );
						}
					} else if ( urlParams.has( filter['url_key'] ) ) {
						urlParams.delete( filter['url_key'] );
					}
				}
	
				if ( urlParams.has('orderby') ) {
					urlParams.delete('orderby');
				}

				if ( urlParams.has('product-page') ) {
					urlParams.delete('product-page');
				}
	
				if ( '' != urlParams.toString() ) {
					hash = urlParams.toString() + '&' + hash;
				}
	
				if ( ! PWF.isEmptyStr(hash) ) {
					hash = '?' + hash;
					if ( hash.endsWith('&') ) {
						hash = hash.slice( 0, -1 );
					}
				}
			}
		
			currentUrlQuery = hash;

			if ( ! PWF.isEmptyObj(filterAttributes) ) {
				if ( false === hash.includes( 'orderby' ) && filterAttributes.hasOwnProperty('orderby') && ! PWF.isEmptyStr(filterAttributes.orderby) ) {
					hash = PWF.isEmptyStr( hash ) ? '?' : ( hash + '&' );
					hash = hash + 'orderby=' + filterAttributes.orderby;
				}
	
				if ( filterAttributes.hasOwnProperty('page') && ! PWF.isEmptyStr(filterAttributes.page) ) {
					if ( pwfCustomization.enablePaginationHash() || isRedirectAjax ) {
						if ( 'product-page' === nextPageString ) {
							if ( filterAttributes.page > 1 ) {
								if ( hash.startsWith('?') ) {
									hash = '&' + hash.substring(1);
								}
								hash = '?product-page=' + filterAttributes.page + hash;
							}
						} else {
							if ( filterAttributes.page > 1 ) {
								let slash = '/';
								if ( false === pwfIsURLHasSlash ) {
									slash = '';
								}
	
								hash = 'page/' + filterAttributes.page + slash + hash;
							}
						}
					}
				}
			}

			let newurl = currentPageURL + hash ;
			if ( ! addPageURL ) {
				newurl = hash;
			}

			return newurl;
		},
		updateBrowserUrlQueryString: function() {
			if ( 'on' !== pwfFilterSetting.browser_hash ) {
				return;
			}
			pwfController.updateBrowserHistory(noPrettyLinks.getBrowserUrlQueryString());
		}
	}

	var pwfWooFilter = {
		init: function() {
			if ( typeof pwffilterVariables !== 'undefined' ) {
				pwfFilterSetting = pwffilterVariables.filter_setting;
				pwfFilterID      = pwffilterVariables.filter_id;
				translatedText   = pwf_woocommerce_filter.translated_text;

				if ( pwfFilterSetting.hasOwnProperty('pagination_type') && ! PWF.isEmptyStr(pwfFilterSetting.pagination_type) ) {
					pwfPaginationType = pwfFilterSetting.pagination_type;
				}
			} else {
				return false; //there is no filter post defined for this page
			}

			$('.pwf-note-list').addClass('empty-active-items');

			let filterData = pwfFilterJSItems;
			pwfWooFilter.setCurrentFilterItems(filterData);
			if ( PWF.isEmptyObj(currentfFilterItems) ) {
				return false; // There is no filter items
			}

			$( document.body ).trigger( 'pwf_filter_js_init_start' );

			pwfWooFilter.setURLhasSlash();
			pwfController.setNextPageString();
			pwfWooFilter.isShortcodeWoo();
			pwfWooFilter.setCSSClassForActiveFilterItems();
			pwfController.setCurrentPageURL();
			pwfController.dbQueryIntegrated();
			pwfFilterEvent.initEvent();
			pwfFilterEvent.wooCatalogSorting();
			pwfFilterEvent.wooCatalogTriggerSorting();
			pwfFilterEvent.noUiSlider();
			pwfPagination.init();
			pwfFilterEvent.setApplyResetButtonStatus();
			pwfFilterEvent.disableApplyButton();

			// @since 1.3.6
			pwfWooFilter.setGlobalVariable();

			$( document.body ).trigger( 'pwf_filter_js_init_end' );
		},
		/**
		 * check if is woocommerce come from woo shortcode
		 */
		isShortcodeWoo: function() {
			let isWooShortCode = false;
			let shopIntegrated = pwffilterVariables.filter_integrated;
			if ( 'yes' === shopIntegrated ) {
				isWooShortCode = false;
			} else if ( pwfFilterSetting.hasOwnProperty('filter_query_type') && 'custom_query' === pwfFilterSetting.filter_query_type ) {
				isWooShortCode = true;
		    }

			return isWooShortCode;
		},
		/**
		 * Change the position for active filter items
		 */
		setCSSClassForActiveFilterItems: function() {
			let activeFiltersSelector = pwfFilterSetting.active_filters_selector;
			if ( ! PWF.isEmptyStr(activeFiltersSelector) && $(activeFiltersSelector).length > 0 ) {
				let filterNote      = $('.pwf-woo-filter-notes');
				$('.pwf-woo-filter-notes').remove();
				$(activeFiltersSelector).each( function( index, current ) {
					if ( index === 0 ) {
						$(current).append(filterNote);
						return;
					}
				});
			}
		},
		setCurrentFilterItems: function( filterItemsData ) {
			/**
			 * assign varible to hold all filter items
			 * without columns, button
			 */
			for ( let key in filterItemsData ) {
				let filter = filterItemsData[key];
				if ( 'column' === filter['item_type'] ) {
					pwfWooFilter.setCurrentFilterItems( filterItemsData[key]['children'] );
				} else if ( 'button' !== filter['item_type'] ) {
					currentfFilterItems[key] = filter;
				}
			}
		},
		/**
		 * Check if Website end with slash or no
		 * Some site doesn't end with slash example .website/shop/ be .website.com/shop
		 */
		setURLhasSlash: function() {
			if ( typeof pwfSetURLHasSlash !== 'undefined' ) {
				// Check if user set pwfIsURLHasSlash inside theme
				pwfIsURLHasSlash = pwfSetURLHasSlash;
			} else {
				let pathName      = window.location.pathname;
				let countPathName = pathName.length;
				if ( countPathName <= 1 ) {
					let paginationSelector = pwfFilterSetting.pagination_selector;
					if ( $( paginationSelector ).find('a').length ) {
						let link = $(paginationSelector).find('a').first().attr('href');
						if ( undefined !== link ) {
							link = link.split('?')[0];
							if ( ! link.endsWith('/') ) {
								pwfIsURLHasSlash = false;
							}
						}
					}
				} else {
					// check end of pathname
					if ( ! pathName.endsWith('/') ) {
						pwfIsURLHasSlash = false;
					}
				}
			}
		},
		/**
		 * Update active Filter Items variable in JS 
		 * If page has selected Items
		 */
		updateActiveFilterItemsInJS: function() {
			let allFilters        = [];
			let selectedItems     =  pwffilterVariables.selected_items;
			let currentHtmlFilter = $('.filter-id-'+ pwfFilterID );

			if ( selectedItems.length < 1 ) {
				return;
			}

			for ( let key in selectedItems ) {
				let intValues = selectedItems[key];
				let values    = [];
				let labels    = [];
				let slugs     = [];
				intValues.forEach( function( value ) {
					values.push( value.toString() );
				});
				let filterItem = pwfFilterActions.getFilterItemDataByUrlKey( key );
				let itemType   = filterItem['item_type'];
				if ( 'priceslider' === itemType ) {
					labels.push( $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('.pwf-field-item-title').find('.text-title').text() );
				} else if ( 'checkboxlist' === itemType ) {
					values.forEach( function( value ) {
						let item = $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('[value="' + value + '"]');
						slugs.push( $(item).attr('data-slug') );
						labels.push( $(item).closest('.pwf-checkbox-label').find('.pwf-title-container').first().find('.text-title').text() );
					});
				} else if ( 'radiolist' === itemType ) {
					let item = $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('[value="' + values[0] + '"]').closest('.pwf-item-label');
					labels = [ $(item).find('.pwf-title-container').find('.text-title').text() ];
					slugs  = [ $(item).find('.pwf-input-container').find('input').attr('data-slug') ];
				} else if ( 'dropdownlist' === itemType ) {
					values.forEach( function( value ) {
						let item  = $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('select').find('option[value="'+ value + '"]');
						labels.push( $(item).attr('data-title') );
						slugs.push( $(item).attr('data-slug') );
					});
				} else if ( 'rating' === itemType ) {
					values.forEach( function( value ) {
						let item = $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('[data-item-value="' + value + '"]');
						if ( 'boxlist' === itemType ) {
							labels.push( $(item).find('.text-title').text() );
						} else if ( 'colorlist' === itemType ) {
							labels.push( $(item).attr('data-item-title') );
						} else if ( 'textlist' === itemType ) {
							labels.push( $(item).find('.text-title').first().text() );
						} else if ( 'rating' === itemType ) {
							labels.push( $(item).attr('data-item-value') );
						}
						slugs.push( $(item).attr('data-slug') );
					});
				}

				let term = {
					'item':   filterItem,
					'values': values,
					'labels': labels,
					'slugs':  slugs,
				};
				allFilters.push( term );
			}
			if ( ! PWF.isEmptyObj(allFilters) ) {
				pwfWooFilter.doingActiveFilterItemsForPageHasQueryString( allFilters );	
				pwfFilterActions.updateNotices();
			}
		},
		checkIfPageURLHasActiveFilterItems: function() {
			let activeFilterItems = pwfWooFilter.getPageURLQueryStrign();
			let pageNumber        = '';
			if ( filterAttributes.hasOwnProperty('page') ) {
				pageNumber = filterAttributes['page'];
			}
			if ( false !== activeFilterItems ) {
				pwfWooFilter.doingActiveFilterItemsForPageHasQueryString( activeFilterItems );
			}

			/**
			 * if page has hash ?=product-page=/d/
			 * You don't need to get products because woocommerce shortcode doing it
			 */
			if ( false !== activeFilterItems || filterAttributes.hasOwnProperty('orderby') ) {
				if ( false !== activeFilterItems ) {
					pwfWooFilter.setOldActiveFilterItems('true');
				}
				// used to hook if page exists in URL for first time and keep page 2 in page url
				// ex http://woodemo.wordpress.test/shop/page/2/?product-category=clothing
				/*if ( filterAttributes.hasOwnProperty('page') && 1 < filterAttributes['page'] ) {
					filterAttributes['page'] = pageNum;
				}*/
				// code for shortcode
				if ( ! PWF.isEmptyStr(pageNumber) ) {
					filterAttributes['page'] = pageNumber;
				}
				pwfAjaxQuery.getProducts('getproducts');
			}
		},
		getPageURLQueryStrign: function() {
			/**
			 * allFilters
			 * hold all active filters contain objects
			 * filterType, currentFilter, values, labels
			 */
			let allFilters = [];
			let queryString = window.location.search;

			if ( PWF.isEmptyStr(queryString) ) {
				return false;
			}

			let urlParams  = new URLSearchParams(queryString);
			let likeFields = [ 'rating', 'checkboxlist', 'radiolist', 'dropdownlist' ];
			for ( let key in currentfFilterItems ) {
				let filter       = currentfFilterItems[key];
				let labels       = [];
				let slugs        = [];
				let values       = [];

				if ( urlParams.has( filter['url_key'] ) || 'priceslider' === filter['item_type'] ) {
					if ( 'priceslider' === filter['item_type'] ) {
						let urlFormat = 'price_url_format';
						if ( 'two' === urlFormat ) {
							let urlKeyMin = 'url_key_min_price';
							let urlKeyMax = 'url_key_max_price';

							if ( urlParams.has( filter[urlKeyMin] ) && urlParams.has( filter[urlKeyMax] ) ) {
								let currentMin = parseInt(urlParams.get( filter[urlKeyMin] ), 10);
								let currentMax = parseInt(urlParams.get( filter[urlKeyMax] ), 10);

								if ( NaN !== currentMin && NaN !== currentMax ) {
									slugs = values = [ currentMin, currentMax ];
									if ( 'priceslider' === filter['item_type'] ) {
										labels.push('priceslider');
									}
								}
								// if there are values or no delete url_key
								urlParams.delete( filter[urlKeyMin] );
								urlParams.delete( filter[urlKeyMin] );
							}
						} else {
							if ( urlParams.has( filter['url_key'] ) ) {
								let slugsData = urlParams.getAll( filter['url_key'] ).toString().split('-');
								let currentMin = parseInt(slugsData[0], 10);
								let currentMax = parseInt(slugsData[1], 10);
								if ( NaN  !== currentMin && NaN !== currentMax ) {
									slugs = values = [ currentMin, currentMax ];
									if ( 'priceslider' === filter['item_type'] ) {
										labels.push('priceslider');
									}
								}
								urlParams.delete( filter['url_key'] );
							}
						}
					} else if ( likeFields.includes( filter['item_type'] ) && ! PWF.isEmptyStr( urlParams.get( filter['url_key'] ) ) ) {
						let slugsData = urlParams.getAll( filter['url_key'] ).toString().split(',');
						if ( ['radiolist','dropdownlist'].includes( filter['item_type'] ) ) {
							slugsData.forEach( function( slug, index ) {
								if ( 0 === index ) {
									labels.push( slug );
									values.push( slug );
									slugs.push( slug );
								}
							});	
						} else {
							slugsData.forEach( function( slug ) {
								labels.push( slug );
								values.push( slug );
								slugs.push( slug );
							});
						}
					} else if ( 'date' === filter['item_type'] ) {
						if ( urlParams.has( filter['url_key_date_before'] ) && urlParams.has( filter['url_key_date_after'] ) ) {
							let dateBefore = urlParams.get( filter['url_key_date_before'] );
							let dateAfter  = urlParams.get( filter['url_key_date_after'] );
							if ( ! PWF.isEmptyStr(dateBefore) && ! PWF.isEmptyStr(dateAfter) ) {
								let slugs = [ dateAfter, dateBefore ];
								slugs.forEach( function( slug ) {
									labels.push(slug);
									values.push(slug);	
								});
							}
							urlParams.delete( filter['url_key_date_before'] );
        					urlParams.delete( filter['url_key_date_after'] );
						}
					} else if ( 'search' === filter['item_type'] ) {
						let searchText = urlParams.get( filter['url_key'] );
						if ( ! PWF.isEmptyStr(searchText) ) {
							slugs = labels = values = [ searchText ];
						}
					}

					if ( ! ['priceslider'].includes( filter['item_type'] ) ) {
						urlParams.delete( filter['url_key'] );
					}

					if ( values.length > 0 ) {
						let term = {
							'item':   filter,
							'values': values,
							'labels': labels,
							'slugs':  slugs,
						};
						allFilters.push( term );
					}
				}
			}

			if ( urlParams.has('orderby') && ! PWF.isEmptyStr( urlParams.get('orderby') ) ) {
				filterAttributes['orderby'] = urlParams.get('orderby');
			}

			if ( urlParams.has('product-page') && parseInt( urlParams.get('product-page') ) > 1 ) {
				filterAttributes['page'] = parseInt( urlParams.get('product-page') );
				urlParams.delete('product-page');
			}
			
			if ( allFilters.length > 0  ) {
				getLabels = true;
				return allFilters;
			} else {
				return false;
			}
		},
		doingActiveFilterItemsForPageHasQueryString: function( filterItems ) {
			if ( false === filterItems ) {
				return;
			}
			filterItems.forEach( function( filter, index ) {
				let currentFilter = filter.item;
				let values        = filter.values;
				let labels        = filter.labels;
				let slugs         = filter.slugs;
	
				if ( 'priceslider' === currentFilter['item_type'] ) {
					pwfFilterActions.processingFilterItem( currentFilter, values, labels, slugs );
				} else {
					values.forEach( function( value, indexed ) {
						if ( ! PWF.isEmptyStr( labels[indexed] ) && undefined !== labels[indexed] ) {
							pwfFilterActions.processingFilterItem( currentFilter, value, labels[indexed], slugs[indexed] );
						}
					});
				}
			});
		},
		UpdateChangeQueryString: function() {
			let currentLink = window.location.href;
			if ( currentLink.includes('%2C') ) {
				currentLink = currentLink.replace( '%2C', ',' );
				window.history.replaceState( { path:currentLink }, '', currentLink );
			}
		},
		setOldActiveFilterItems: function( str ) {
			// This used when click reset button
			oldActiveFilterItems = ('true' === str ) ? true : false;
		},
		isFilterStartAuto: function() {
			let filtering_starts = pwfFilterSetting.filtering_starts;
			if ( pwfIsResponsiveView ) {
				filtering_starts = pwfFilterSetting.responsive_filtering_starts;
			}

			return ( 'auto' === filtering_starts );
		},
		getCurrencyTemplate: function() {
			let currencySymbol = pwf_woocommerce_filter.currency_symbol;
			let template = '<span class="pwf-currency-symbol">' + currencySymbol + '</span>';
			return template;
		},
		setGlobalVariable: function() {
			pwfGetJsUsedFuntion = {
				getPaginationType : function() {
					return pwfPagination.getType();
				}
			}
		}
	};

	var pwfCustomization = {
		isDefine: function() {
			if ( typeof pwfWooFilterCustomization !== 'undefined') {
				if ( pwfWooFilterCustomization.hasOwnProperty('filterID') ) {
					if ( Array.isArray( pwfWooFilterCustomization.filterID ) ) {
						return pwfWooFilterCustomization.filterID.includes( pwfFilterID );
					} else if ( pwfFilterID === pwfWooFilterCustomization.filterID ) {
						return true;
					} else {
						return false;
					}
				}
				return true;
			}
			return false;
		},
		getInfiniteDistance: function() {
			let distance = 0;
			if ( pwfCustomization.isDefine() && pwfWooFilterCustomization.hasOwnProperty('infiniteDisatance') && ! PWF.isEmptyStr( pwfWooFilterCustomization.infiniteDisatance ) ) {
				distance = pwfWooFilterCustomization.infiniteDisatance;
			}			
			return distance;
		},
		enablePaginationHash: function() {
			// this option avialable only when pagination type load more or infinite scroll
			let enable = true;
			if ( 'numbers' !== pwfPagination.getType() ) {
				if ( pwfCustomization.isDefine() && pwfWooFilterCustomization.hasOwnProperty('enablePaginationHash') && true === pwfWooFilterCustomization.enablePaginationHash ) {
					enable = true;
				} else {
					enable = false;
				}
			}
			return enable;
		},
		responsivePaginationType: function() {
			let paginationType = '';
			if ( pwfCustomization.isDefine() && pwfWooFilterCustomization.hasOwnProperty('responsivePagination') && '' !== pwfWooFilterCustomization.responsivePagination ) {
				let responsive = pwfWooFilterCustomization.responsivePagination;
				if ( responsive.hasOwnProperty('type') && ! PWF.isEmptyStr(responsive.type) && responsive.hasOwnProperty('maxScreenWidth') && '' !== responsive.maxScreenWidth ) {
					if ( window.matchMedia( '(max-width: '+ parseInt( responsive.maxScreenWidth ) +'px)' ).matches ) {
						paginationType = responsive.type;
					}
				}				
			}
			return paginationType;
		},
		filterButtonSpeed: function() {
			let speed = 400;
			if ( pwfCustomization.isDefine() && pwfWooFilterCustomization.hasOwnProperty('filterButtonSpeed') && '' !== pwfWooFilterCustomization.filterButtonSpeed ) {
				let checkSpeed = parseInt( pwfWooFilterCustomization.filterButtonSpeed );
				if ( checkSpeed !== NaN  ) {
					speed = checkSpeed;
				}
			}
			return speed;
		},
	};

	var pwfPagination = {
		getType: function() {
			if ( ! PWF.isEmptyStr( pwfCustomization.responsivePaginationType() ) ) {
				pwfPaginationType = pwfCustomization.responsivePaginationType();
			}
			return pwfPaginationType; // can be numbers, infinite, load more button
		},
		init: function() {
			let usecomponents      = pwfFilterSetting.usecomponents;
			let paginationSelector = pwfFilterSetting.pagination_selector;

			if ( usecomponents.includes('pagination') && ! PWF.isEmptyStr(paginationSelector) && $(paginationSelector).length && 'on' === pwfFilterSetting.pagination_ajax ) {
				if ( 'numbers' === pwfPagination.getType() ) {
					pwfPagination.addAjaxToPagination();
				}
			}
		},
		getNextPage: function() {
			let currentPage = 1;
			let nextPage    = '';
			if ( filterAttributes.hasOwnProperty('page') && 1 < filterAttributes.page ) {
				currentPage = filterAttributes['page'];
				nextPage    = currentPage + 1;
			} else {
				let paginationSelector = pwfFilterSetting.pagination_selector;
				let pages = $(paginationSelector).find('a');
				$(pages).each( function() {
					let pageNum = null;
					if ( 'product-page' === nextPageString ) {
						let link = $(this).attr('href');
						pageNum  = pwfPagination.extractPageNumberFromProductPage( link );
					} else {
						let link = $(this).attr('href').split('?')[0];
						pageNum  = pwfPagination.extractPageNumberFromURL( link );
					}

					if ( null !== pageNum && pageNum > currentPage  ) {
						nextPage = currentPage + 1 ;
						return false;
					}
				});
			}
			
			return nextPage;
		},
		buttonLoader: function() {
			let loader = '<span class="pwf-loader"></span>';
			loader     = '<span class="pwf-button-loader">' + loader + '</span>';
			return loader;
		},
		infiniteLoader: function() {
			let loader          = '<span class="pwf-infinite-loader"><span class="pwf-bounce pwf-b1"></span><span class="pwf-bounce pwf-b2"></span><span class="pwf-bounce pwf-b3"></span></span>';
			loader = '<div class="pwf-button-loader">' + loader + '</div>';
			return loader;
		},
		addAjaxToPagination: function() {
			let paginationSelector = pwfFilterSetting.pagination_selector;
			// ajax pagination
			$('body').on('click', paginationSelector + ' a', function( event ) {
				event.preventDefault();
				let pageNum = null;
				
				pageNum =  pwfPagination.extractPageNumberFromProductPage( $(this).attr('href') );
				if ( null === pageNum ) { 
					// for /page/2
					pageNum = pwfPagination.extractPageNumberFromURL( $(this).attr('href').split('?')[0] );
				}

				if ( null !== pageNum ) {
					getProductsOnly          = true;
					filterAttributes['page'] = pageNum;
					pwfAjaxQuery.getProducts('getproducts');
				}
			});

			/**
			 * since 1.3.6
			 * triger event when click page number
			 */
			$('body').on('pwfTriggerPageNumber', function ( event, pageNum ) {
				if (  ! PWF.isEmptyStr(pageNum) ) {
					if ( filterAttributes['page'] !== parseInt( pageNum ) ) {
						filterAttributes['page'] = pageNum;
						pwfAjaxQuery.getProducts('getproducts');
					}
				}
			});
		},
		extractPageNumberFromURL: function( pageURL ) {
			// used for default pagination
			let pageNum    = pageURL.match(/\/\d+\/$/);
			if ( false === pwfIsURLHasSlash ) {
				pageNum    = pageURL.match(/\d+$/);
			}
			if ( null !== pageNum ) {
				pageNum = parseInt( pageNum[0].match(/(\d+)/)[0] );
				return pageNum;
			}

			if ( null === pageNum ) {
				// some short code use page/2 withour /page/2/
				pageNum = pageURL.match(/\d+$/);
				if ( null !== pageNum ) {
					pageNum = parseInt( pageNum[0].match(/(\d+)/)[0] );
					return pageNum;
				}
			}

			return null;
		},
		extractPageNumberFromProductPage: function( pageURL ) {
			let pageNum    = pageURL.match(/product-page=\d+/);
			if ( null !== pageNum ) {
				pageNum = parseInt( pageNum[0].match( new RegExp("\\d+") )[0] );
				return pageNum;
			}

			return null;
		}
	};

	var pwfFilterEvent = {
		initEvent: function() {
			pwfFilterEvent.select2();
			pwfFilterEvent.triggerPostPerPage();
			pwfFilterEvent.checkToggle();
			
			pwfFilterEvent.canvasApplyResetButton();
			/* End change the window resize */

			$('.pwf-woo-filter').on('click', '.pwf-reset-button', function( event ) {
				event.preventDefault();
				pwfFilterEvent.resetFilter('resetButton');
			});
	
			$('.pwf-woo-filter').on('click', '.pwf-filter-button', function( event ) {
				event.preventDefault();
				pwfFilterEvent.submitFilter('submitButton');
			});

			// display filter as button
			if ( 'button' === pwfFilterSetting.display_filter_as ) {
				if ( 'hide' === pwfFilterSetting.filter_button_state ) {
					$('.pwf-woo-filter').hide().removeClass('pwf-hidden');
				}
				pwfFilterEvent.filterButtonASTitle();
			}
	
			// pwf-remove-filter
			$('body').on('click', '.pwf-note-item', function( event ) {
				event.preventDefault();

				if ( $(this).hasClass('pwf-clear-all-note') ) {
					pwfFilterEvent.resetFilter('clearall');
				} else {
					let note = $( this );
					let name = $( note ).attr('data-item-key');
					let value = '';
					if ( $(note).hasClass('pwf-range-slider-note') || $(note).hasClass('pwf-date-note') || $(note).hasClass('pwf-search-note') ) {
						value = '';
					} else {
						value = $(note).attr('data-item-value');
					}
		
					$(note).slideUp( 'fast', function() {
						$(this).remove();
					});
					
					let currentFilter = pwfFilterActions.getFilterItemDataByUrlKey( name );
					if ( ! pwfWooFilter.isFilterStartAuto() ) {
						pwfFilterActions.removeSelectedValueFromHTMLFilter( currentFilter, value );
					}
					pwfFilterActions.processingFilterItem( currentFilter, value, '', '' );
					pwfAjaxQuery.getProducts();
				}
			});
	
			$('.pwf-woo-filter').on('click', '.pwf-item-label > .pwf-toggle, .pwf-field-item-title', function( event ) {
				event.preventDefault();
				let currentItem   = $(this);
				let openCssClass  = 'pwf-collapsed-open';
				let closeCssClass = 'pwf-collapsed-close';

				if ( $(currentItem).hasClass('pwf-field-item-title') ) {
					let fieldItem = $(currentItem).closest('.pwf-field-item');
					if ( fieldItem.hasClass(closeCssClass) ) {
						fieldItem.removeClass(closeCssClass).addClass(openCssClass);
					} else if ( fieldItem.hasClass(openCssClass) ) {
						fieldItem.removeClass('pwf-collapsed-open').addClass(closeCssClass);
					}
					$(fieldItem).find('.pwf-field-item-container').slideToggle();
				} else {
					let fieldItem = $(currentItem).closest('.pwf-item');
					if ( fieldItem.hasClass(closeCssClass) ) {
						fieldItem.removeClass(closeCssClass).addClass(openCssClass);
					} else if ( fieldItem.hasClass(openCssClass) ) {
						fieldItem.removeClass(openCssClass).addClass(closeCssClass);
					}
					$(fieldItem).find('.pwf-children:first').slideToggle();
				}
			});
			
			/*
			 * Prevent events mouseenter, mouseleave
			 * from working on touch screen
			 */
			var deviceHasTouch = ("ontouchstart" in document.documentElement);
			if( ! deviceHasTouch ) {
				// Checkbox list
				$(document).on('mouseenter', '.pwf-checkbox-click-area', function( event ) {
					if ( ! $(this).closest('.pwf-item-label').hasClass('pwf-ui-state-hover') ) {
						$(this).closest('.pwf-item-label').addClass('pwf-ui-state-hover')
					}
				});
				$(document).on('mouseleave', '.pwf-checkbox-click-area', function( event ) {
					if( $(this).closest('.pwf-item-label').hasClass('pwf-ui-state-hover') ) {
						$(this).closest('.pwf-item-label').removeClass('pwf-ui-state-hover')
					}
				});

				// Radio list
				$(document).on('mouseenter', '.pwf-radiolist-label .pwf-input-container, .pwf-radiolist-label .pwf-title-container', function( event ) {
					if( ! $(this).closest('.pwf-item-label').hasClass('pwf-ui-state-hover') ) {
						$(this).closest('.pwf-item-label').addClass('pwf-ui-state-hover')
					}
				});
				$(document).on('mouseleave', '.pwf-radiolist-label .pwf-input-container, .pwf-radiolist-label .pwf-title-container', function( event ) {
					if( $(this).closest('.pwf-item-label').hasClass('pwf-ui-state-hover') ) {
						$(this).closest('.pwf-item-label').removeClass('pwf-ui-state-hover')
					}
				});

				// star rate
				$(document).on('mouseenter', '.pwf-star-rating-item', function( event ) {
					if ( ! $(this).hasClass('pwf-ui-state-hover') ) {
						$(this).addClass('pwf-ui-state-hover')
					}
				});
				$(document).on('mouseleave', '.pwf-star-rating-item', function( event ) {
					if( $(this).hasClass('pwf-ui-state-hover') ) {
						$(this).removeClass('pwf-ui-state-hover')
					}
				});
			}
			
			$('.pwf-woo-filter').on('click', '.pwf-checkbox-click-area', function( event ) {
				event.preventDefault();
	
				// Don't do any thing if this item is disabled
				if ( $(this).closest('.pwf-checkboxlist-item').hasClass('pwf-disabled') ) {
					return false;
				}
	
				let checkbox = $(this).closest('.pwf-checkbox-label').find('.pwf-input-checkbox');
				let label    = $(checkbox).closest('.pwf-item-label').find('.text-title').text();
				let value    = $(checkbox).val();	
				let name     = $(checkbox).attr('name');
				let slug     = $(checkbox).attr('data-slug');
	
				let currentFilter    = pwfFilterActions.getFilterItemDataByUrlKey( name );
				let checkboxlistItem = $(this).closest('.pwf-checkboxlist-item');
				let isChecked     = false;
				if ( $( checkbox ).prop("checked") == true ) {
					$( checkboxlistItem ).removeClass('checked');
					$( checkbox ).prop('checked', false );
				} else {
					isChecked = true;
					$( checkboxlistItem ).addClass('checked');
					$( checkbox ).prop('checked', true );
				}
	
				pwfFilterActions.processingFilterItem( currentFilter, value, label, slug );
	
				if ( isChecked ) {
					$( checkbox ).prop('checked', true );
					let itemparents = $( this ).parents('.pwf-checkboxlist-item');
					let ulchildren  = $( this ).closest('.pwf-checkbox-label').next('.pwf-children');
					if ( itemparents.length > 0 ) {
						$( itemparents ).each( function ( index, currentitem ) {
							if ( 0 == index ) {
								return;
							}
							
							let checkbox = $( currentitem ).find('.pwf-item-label').first().find('.pwf-input-checkbox');
							if ( $(checkbox).prop("checked") == true ) {
								let labelparent  = $(checkbox).closest('.pwf-item-label').find('.text-title').text();
								let valueparent  = $(checkbox).val();
								let nameparent   = $(checkbox).attr('name');
								let filterParent = pwfFilterActions.getFilterItemDataByUrlKey( nameparent );
	
								$( checkbox ).prop('checked', false );
								$( checkbox ).closest('.pwf-checkboxlist-item').removeClass('checked')
								pwfFilterActions.processingFilterItem( filterParent, valueparent, labelparent );
							}
						});
					}
					
					if ( ulchildren.length > 0 ) {
						let itemchildren = $( ulchildren ).find('.pwf-input-checkbox');
						if ( itemchildren.length > 0 ) {
							$( itemchildren ).each( function ( index, currentitem ) {
								let checkbox = $( currentitem );
								if ( $(checkbox).prop("checked") == true ) {
									let labelchild  = $(checkbox).closest('.pwf-item-label').find('.text-title').text();
									let valuechild  = $(checkbox).val();
									let namechild   = $(checkbox).attr('name');
									let filterChild = pwfFilterActions.getFilterItemDataByUrlKey( namechild );
			
									$(checkbox).prop( 'checked', false );
									$(checkbox).closest('.pwf-checkboxlist-item').removeClass('checked')
									pwfFilterActions.processingFilterItem( filterChild, valuechild, labelchild );
								}
							});
						}
					}
				}
				
				pwfAjaxQuery.getProducts();
			});
	
			$('.pwf-woo-filter').on('click', '.pwf-radiolist-label .pwf-input-container, .pwf-radiolist-label .pwf-title-container', function( event ) {
				event.preventDefault();
	
				if ( $(this).closest('.pwf-radiolist-item').hasClass('pwf-disabled') ) {
					return false;
				}
	
				let radio      = $(this).closest('.pwf-radiolist-label').find('.pwf-input-radio');
				let label      = $(radio).closest('.pwf-item-label').find('.text-title').text();
				let value      = $(radio).attr('value');
				let name       = $(radio).attr('name');
				let slug       = $(radio).attr('data-slug');
				let filterItem = pwfFilterActions.getFilterItemDataByUrlKey( name );
	
				$( this ).closest('.pwf-field-item-radiolist').find('.checked').removeClass('checked');
				if ( $( radio ).prop("checked") == true ) {
					$( radio ).prop('checked', false );
					
				} else {
					$( radio ).prop('checked', true );
					$( this ).closest('.pwf-radiolist-label').addClass('checked');
				}
	
				pwfFilterActions.processingFilterItem( filterItem, value, label, slug );
				pwfAjaxQuery.getProducts();
			});
	
			$('.pwf-woo-filter').on('change', '.pwf-dropdownlist-item-default', function( event ) {
				pwfFilterEvent.ProcessingSelect( this );
			});
			
			/**
			 * search field
			 * @since 1.1.3
			 */
			$('.pwf-woo-filter').on('click', '.pwf-star-rating-item', function( event ) {
				event.preventDefault();
				if ( $(this).hasClass('pwf-disabled') ) {
					return false;
				}
				let mainField = $(this).closest('.pwf-field-item-rating');
				if ( $(mainField).hasClass('pwf-rating-radio-type') ) {
					$(mainField).find('.checked').removeClass('checked');
					$(this).addClass('checked');
				} else {
					$(this).addClass('checked');
				}

				let label      = $(this).attr('data-item-value');
				let value      = $(this).attr('data-item-value');
				let name       = $(mainField).attr('data-item-key');
				let slug       = $(this).attr('data-slug');
				let filterItem = pwfFilterActions.getFilterItemDataByUrlKey( name );
				
				pwfFilterActions.processingFilterItem( filterItem, value, label, slug );
				pwfAjaxQuery.getProducts();
			});
		},
		filterButtonASTitle: function() {
			$('body').on('click', '.pwf-filter-as-button-title', function( event ) {
				let titleItem = $(this).closest('.pwf-filter-as-button-header');
				if( $(titleItem).hasClass('pwf-btn-opened') ) {
					$(titleItem).removeClass('pwf-btn-opened').addClass('pwf-btn-closed');
				} else {
					$(titleItem).removeClass('pwf-btn-closed').addClass('pwf-btn-opened');
				}
				$('.pwf-woo-filter').slideToggle( pwfCustomization.filterButtonSpeed() );
			});
		},
		checkToggle: function() {
			let ActiveFiltersKey = [];
			if ( ! PWF.isEmptyObj(activeFilterItems) ) {
				ActiveFiltersKey = Object.keys(activeFilterItems);
			}

			let toogleClose = $('.pwf-field-item'); //find('.pwf-collapsed-close');
			toogleClose.each( function( index, current ) {
				let key = $(current).attr('data-item-key');
				if ( $(current).hasClass('pwf-collapsed-close') ) {
					if ( ActiveFiltersKey.length && ActiveFiltersKey.includes( key ) ) {
						$(current).removeClass('pwf-collapsed-close').addClass('pwf-collapsed-open');
					} else {
						$(current).find('.pwf-field-item-container').hide();
					}
				}
			});

			let itemToogleClose = $('.pwf-item');
			itemToogleClose.each( function( index, current ) {
				if ( $(current).hasClass('pwf-collapsed-close') ) {
					let parentField = $(current).closest('.pwf-field-item');
					let key         = $(parentField).attr('data-item-key');
					if ( ActiveFiltersKey.length && ActiveFiltersKey.includes( key ) ) {
						if ( $(current).hasClass('pwf-checkboxlist-item') || $(current).hasClass('pwf-radiolist-item') || $(current).hasClass('pwf-textlist-item') ) {
							let itemChildrenValues = [];

							if ( $(current).hasClass('pwf-checkboxlist-item') ) {

								let checkboxes = $(current).find('.pwf-checkboxlist-item .pwf-input-checkbox'); 
								if ( checkboxes.length ) {
									checkboxes.each( function( index, item ) {
										itemChildrenValues.push( $(item).val() );
									});
								}
							} else if ( $(current).hasClass('pwf-radiolist-item') ) {
								let radioLists = $(current).find('.pwf-children').find('.pwf-input-radio:checked');
								if ( radioLists.length ) {
									radioLists.each( function( index, item ) {
										itemChildrenValues.push( $(item).val() );
									});
								}
							} else if ( $(current).hasClass('pwf-textlist-item') ) {
								let textlists = $(current).find('.pwf-textlist-item');
								if ( textlists.length ) {
									textlists.each( function( index, item ) {
										itemChildrenValues.push( $(item).attr('data-item-value') );
									});
								}
							}

							let childItemActive     = false;
							let currentFilterValues = activeFilterItems[key]['values'];

							if ( itemChildrenValues.length ) {
								currentFilterValues.every( function( value, index, currentFilterValues ) {
									if ( itemChildrenValues.includes( value ) ) {
										childItemActive = true;
										return true;
									}
								});
							}

							if ( childItemActive ) {
								$(current).removeClass('pwf-collapsed-close').addClass('pwf-collapsed-open');
							} else {
								$( current ).find('.pwf-item-inner:first').find('.pwf-children:first').hide();
							}
						}						
					} else {
						$( current ).find('.pwf-item-inner:first').find('.pwf-children:first').hide();
					}
				}
			});
		},
		wooCatalogSorting: function() {
			let usecomponents  = pwfFilterSetting.usecomponents;
			let orderbySelector = pwfFilterSetting.sorting_selector;

			if ( usecomponents.includes('sorting') && ! PWF.isEmptyStr(orderbySelector) ) {
				if ( 'on' != pwfFilterSetting.sorting_ajax ) {
					// Ajax disable
					$( orderbySelector ).on( 'submit', function( event ) {
						if ( ! PWF.isEmptyObj(activeFilterItems) ) {
							event.preventDefault();
						}
					});

					$( orderbySelector ).on( 'change', 'select.orderby', function( event ) {
						if ( ! PWF.isEmptyObj(activeFilterItems) ) {
							event.preventDefault();
						}
					});

				} else if ( 'on' == pwfFilterSetting.sorting_ajax ) {
					// Sorting Ajax enabled
					$( orderbySelector ).on( 'submit', function( event ) {
						event.preventDefault();
					});

					$( orderbySelector ).on( 'change', 'select.orderby', function( event) {
						event.preventDefault();
						pwfFilterEvent.wooCatalogDoingSorting( $(this).val() );
					});
				}
			}
		},
		wooCatalogDoingSorting: function( currentValue ) {
			if ( PWF.isEmptyStr(currentValue) ) {
				return false;
			}

			if ( $('[data-item-key="orderby"]').length > 0 ) {
				let filter = pwfFilterActions.getFilterItemDataByUrlKey( 'orderby' );
					if ( 'radiolist' === filter['item_type'] ) {
						let inputs = $('[data-item-key="orderby"] [name="orderby"]');
						$('[data-item-key="orderby"] [name="orderby"]').prop( "checked", false );
						for ( let index = 0; index < inputs.length; index++ ){
							let value = $(inputs[index]).attr('value');
							if ( value === currentValue ) {
								$( inputs[index] ).prop( "checked", "true" );
								$( inputs[index] ).closest('.pwf-checkboxlist-item').addClass('checked');
							}
						}
						let orderItem = $('[data-item-key="orderby"] [name="orderby"][value="'+ currentValue +'"]').closest('.pwf-input-container');
						$(orderItem).trigger('click');
						$(orderItem).trigger('change');
					} else if ( 'dropdownlist' === filter['item_type'] ) {
						$('[data-item-key="orderby"] [name="orderby"] option[value="'+ currentValue +'"]').prop( "selected", "true" );
						$('[data-item-key="orderby"] [name="orderby"]').trigger('change');
					}	
			}

			if ( 'numbers' !== pwfPagination.getType() ) {
				if ( filterAttributes.hasOwnProperty('page') && 1 < filterAttributes.page ) {
					delete filterAttributes['page']; // fix orderby after load more button working
				}
			}
			filterAttributes['orderby'] = currentValue;
			pwfAjaxQuery.getProducts( 'getproducts', 'sorting');
		},
		wooCatalogTriggerSorting: function() {
			$('body').on('pwfTriggerSorting', function ( event, json ) {
				if ( ! PWF.isEmptyStr(json.orderby) ) {
					pwfFilterEvent.wooCatalogDoingSorting( json.orderby );
				}
			});
		},
		triggerPostPerPage: function() {
			$('body').on('pwfTriggerPostPerPage', function ( event, postPerPage ) {
				if ( ! PWF.isEmptyStr(postPerPage) ) {
					filterAttributes['per_page'] = parseInt( postPerPage );
					pwfAjaxQuery.getProducts( 'getproducts', 'per_page');
				}
			});
		},
		nouiSiderValidNumber: function ( value, usedFun ) {
			if ( 'parseInt' === usedFun ) {
				return parseInt( value );
			} else {
				return parseFloat( value );
			}
		},
		noUiSlider: function() {
			// noUi-target
			if ( $('.pwf-range-slider').length > 0 ) {
				let direction = ( $('body').hasClass('rtl') ) ? 'rtl' : 'ltr';
				$('.pwf-range-slider').each( function() {
					if( ! $(this).hasClass('noUi-target') ) {
						let currentSlider = $(this);
						let rangeSlider   = this;
						let currentMin    = $(rangeSlider).attr('data-current-min');
						let currentMax    = $(rangeSlider).attr('data-current-max');
						let minPrice      = $(rangeSlider).attr('data-min');
						let maxPrice      = $(rangeSlider).attr('data-max');
						let tooltip       = $(rangeSlider).attr('data-tooltip');
						let step          = $(rangeSlider).attr('data-step');

						let usedFun = 'parseInt';
						step = step.toString();
						if ( step.includes('.') ) {
							usedFun = 'parseFloat';
						}

						if ( 'true' === tooltip ) {
							tooltip = true;
						} else {
							tooltip = false;
						}
						
						if ( step <= 0 ) {
							step = 1;
						} else {
							step = pwfFilterEvent.nouiSiderValidNumber(step, usedFun);
						}

						let rangeSliderArgs = {
							step: step,
							behaviour: 'drag',
							direction: direction,
							start: [ pwfFilterEvent.nouiSiderValidNumber(currentMin, usedFun), pwfFilterEvent.nouiSiderValidNumber(currentMax, usedFun) ],
							connect: true,
							tooltips: tooltip,
							range: {
								'min': parseInt(minPrice),
								'max': parseInt(maxPrice),
							},
							format: {
								to: function (value) {
									return pwfFilterEvent.nouiSiderValidNumber( value, usedFun );
								},
								from: function (value) {
									return pwfFilterEvent.nouiSiderValidNumber( value, usedFun );
								}
							}
						};

						let limit = $(rangeSlider).attr('data-limit');
						if ( typeof limit !== typeof undefined && limit !== false ) {
							rangeSliderArgs['limit'] = parseInt( limit );
						}
						
						noUiSlider.create( rangeSlider, rangeSliderArgs );

						// when user input number in min and max input fields
						rangeSlider.noUiSlider.on( 'set', function( values, handle, unencoded, tap, positions, noUiSlider ) {
							let currentMin    = $(rangeSlider).attr('data-current-min');
							let currentMax    = $(rangeSlider).attr('data-current-max');

							if ( pwfFilterEvent.nouiSiderValidNumber(currentMin, usedFun) !== values[0] || pwfFilterEvent.nouiSiderValidNumber(currentMax, usedFun) !== values[1] ) {
								let name          = $(rangeSlider).closest('.pwf-field-item').attr('data-item-key');
								let currentFilter = pwfFilterActions.getFilterItemDataByUrlKey( name );
								let label         = currentFilter['title'];
								pwfFilterActions.processingFilterItem( currentFilter, values, label );
								pwfAjaxQuery.getProducts();
							}
						});

						let rangeItem     = $(currentSlider).closest('.pwf-range-slider-wrap');
						let minPriceInput = document.getElementById( $(rangeItem).find('.pwf-min-value').attr('id'));
						let maxPriceInput = document.getElementById( $(rangeItem).find('.pwf-max-value').attr('id'));
						let labelMinPrice = document.getElementById( $(rangeItem).find('.pwf-from').attr('id'));
						let labelMaxPrice = document.getElementById( $(rangeItem).find('.pwf-to').attr('id'));

						rangeSlider.noUiSlider.on('update', function ( values, handle ) {
							if ( 0 === handle ) {
								if ( null !== minPriceInput ) {
									minPriceInput.value = values[handle];
								}
								if ( null !== labelMinPrice ) {
									$(labelMinPrice).text( values[handle] );
								}
							} else if ( 1 === handle ){
								if ( null !== maxPriceInput ) {
									maxPriceInput.value = values[handle];
								}
								if ( null !== labelMaxPrice ) {
									$(labelMaxPrice).text( values[handle] );
								}
							} 
							
						});
						if ( null !== minPriceInput ) {
							minPriceInput.addEventListener('change', function () {
								let min = parseInt( $(this).attr('min') );
								let max = parseInt( $(this).attr('max') );
								if ( this.value < min ) {
									this.value = min;
								} else if ( this.value > max ) {
									this.value = max;
								}
								rangeSlider.noUiSlider.set( [ this.value, null ] );
							});
							maxPriceInput.addEventListener('change', function () {
								let min = parseInt( $(this).attr('min') );
								let max = parseInt( $(this).attr('max') );
								if ( this.value < min ) {
									this.value = min;
								} else if  ( this.value > max ) {
									this.value = max;
								}
								rangeSlider.noUiSlider.set( [ null, this.value ] );
							});
						}
					}
				});
			}
		},
		select2: function() {
			if ( $('.pwf-dropdownlist-item-select2').length > 0 ) {
				let allSelect2 = $('.pwf-dropdownlist-item-select2');
				$(allSelect2).each( function() {
					pwfFilterEvent.addSelect2Event(this);
				});
			}
		},
		addSelect2Event: function( item ) {
			let multiple    = false;
			let values      = [];
			let cssClass    = 'pwf-customize-select2';
			let selected    = $(item).find('[selected]');
			let placeHolder = '';

			$(selected).each( function() {
				values.push( $(this).val() );
			});

			if ( $(item).hasClass('pwf-has-multiple') ) {
				multiple    = true;
				placeHolder = 'Select ...';
				
			}

			$(item).select2({ width: '100%', multiple: multiple, dropdownCssClass: cssClass, placeholder: placeHolder });

			if ( values.length && values.length > 1 ) {
				$(item).val( values );
				$(item).trigger('change');
			}

			$(item).on('select2:select', function( e ){
				pwfFilterEvent.ProcessingSelect2( this, e.params.data );
			});
			$(item).on('select2:unselect', function( e ){
				pwfFilterEvent.ProcessingSelect2( this, e.params.data );
			});
		},
		ProcessingSelect2: function( item, selected ) {
			let value      = selected.id;
			let name       = $(item).attr('name');
			let label      = $(item).find('option[value="'+ value +'"]').attr('data-title');
			let slug       = $(item).find('option[value="'+ value +'"]').attr('data-slug');
			let filterItem = pwfFilterActions.getFilterItemDataByUrlKey( name );

			pwfFilterActions.processingFilterItem( filterItem, value, label, slug );
			pwfAjaxQuery.getProducts();
		},
		ProcessingSelect: function( item ) {
			let selectedOption = $(item).find('option:selected');
			
			let label      = $(selectedOption).attr('data-title');
			let slug       = $(selectedOption).attr('data-slug');
			let value      = $(item).val();
			let name       = $(item).attr('name');
			let filterItem = pwfFilterActions.getFilterItemDataByUrlKey( name );

			pwfFilterActions.processingFilterItem( filterItem, value, label, slug );
			pwfAjaxQuery.getProducts();
		},
		checkFilterItemsHasSortingItem: function( selected = '' ) {
			let filter_has_orderby = false
			let orderbyValue       = '';
			for ( let key in activeFilterItems ) {
				if ( key === 'orderby' ) {
					filter_has_orderby = true;
					orderbyValue = activeFilterItems['orderby']['values'][0];
				}
			}

			if ( false === filter_has_orderby && 'default' === selected ) {
				filter_has_orderby = true;
				orderbyValue       = 'menu_order';
			}

			if ( filter_has_orderby ) {
				let usecomponents = pwfFilterSetting.usecomponents;
				if ( usecomponents.includes('sorting') && 'on' == pwfFilterSetting.sorting_ajax && ! PWF.isEmptyStr(pwfFilterSetting.sorting_selector) ) {
					let orderbySelector = pwfFilterSetting.sorting_selector;
					if ( 'showall' === orderbyValue ) {
						orderbyValue = 'menu_order';
					}
					$( orderbySelector ).find( 'option[value="'+ orderbyValue +'"]' ).prop( "selected", "true" );
				}
			}
		},
		submitFilter: function( from = '' ) {
			if ( ! PWF.isEmptyObj(activeFilterItems) || true === oldActiveFilterItems ) {
				pwfAjaxQuery.getProducts('getproducts', from );
			}
		},
		resetFilter: function( from = '' ) {
			$('.pwf-note-list').empty().addClass('empty-active-items');
			const currentHtmlFilter = $('.filter-id-'+ pwfFilterID );
			for ( let key in currentfFilterItems ) {
				let filterItem   = currentfFilterItems[key];
				let itemType     = filterItem['item_type'];
				let itemSelector = $(currentHtmlFilter).find('[data-item-key="' + filterItem['url_key'] + '"]');

				if ( 'checkboxlist' === itemType ) {
					let items = $(itemSelector).find('.pwf-item.checked');
					$(items).each( function( index, item ) {
						$(item).removeClass('checked');
						$(item).find('input[type=checkbox]').first().prop( "checked", false );
					});
				} else if ( 'radiolist' === itemType ) {
					let items = $(itemSelector).find('.checked');
					$(items).each( function( index, item ) {
						$(item).removeClass('checked');
						$(item).find('input[type=radio]').first().prop( "checked", false );
					});

					let showAll = $(itemSelector).find('input[data-slug="showall"]');
					if ( showAll.length ) {
						$(showAll).prop( "checked", true );
						$(showAll).closest('.pwf-item-label').addClass('checked');
					}
				} else if ( 'dropdownlist' === itemType ) {
					let item = $(itemSelector).find('select');
					item.prop('selectedIndex', 0);

					if ( $(item).hasClass('pwf-dropdownlist-item-select2') ) {
						$(item).select2("destroy");
						pwfFilterEvent.addSelect2Event(item);
					}
				} else if ( 'priceslider' === itemType ) {
					let rangeSliderSelector = $(itemSelector).find('.pwf-range-slider');
					if ( $(rangeSliderSelector).hasClass('noUi-target') ) {
						let rangeSlider   = document.getElementById( $(rangeSliderSelector).attr('id') );
						let minValue      = $(rangeSlider).attr('data-min');
						let maxValue      = $(rangeSlider).attr('data-max');
						rangeSlider.noUiSlider.updateOptions( {
							start: [ parseInt(minValue), parseInt(maxValue) ],
						}, false );
					}
				}
			}

			activeFilterItems = {};
			currentUrlQuery   = '';
			resetButtonClicked = true; // used when click reset button to remove orderby

			if ( filterAttributes.hasOwnProperty('per_page') ) {
				let perPage = filterAttributes.per_page;
				filterAttributes = {};
				filterAttributes['per_page'] = perPage;
			} else {
				filterAttributes = {};
			}

			pwfFilterEvent.setApplyResetButtonStatus();
			pwfAjaxQuery.getProducts( 'getproducts', from );
		},
		disableApplyButton: function() {
			$('.pwf-item-button.filter-button').addClass('pwf-disabled-btn').attr("disabled", true);
			// mobile
		},
		setApplyResetButtonStatus: function() {
			let cssSelector = '.pwf-reset-button, .pwf-filter-button';
			if ( PWF.isEmptyObj(activeFilterItems) && false === oldActiveFilterItems ) {
				$(cssSelector).addClass('pwf-disabled-btn').prop("disabled", true);
			} else {
				$(cssSelector).removeClass('pwf-disabled-btn').prop( 'disabled', false );
			}
		},
		canvasApplyResetButton: function() {
			$('.pwf-off-canvas').on('click', '.pwf-reset-button', function( event ) {
				event.preventDefault();
				pwfFilterEvent.resetFilter('canvas-resetButton');
			});
			$('.pwf-off-canvas').on('click', '.pwf-filter-button', function( event ) {
				event.preventDefault();
				pwfFilterEvent.submitFilter('canvas-submitButton');
			});
		}
	};
	
	var pwfFilterActions = {
		getFilterItemDataByUrlKey: function( urlKey ) {
			if ( PWF.isEmptyObj(currentfFilterItems)  ) {
				return false;
			}
			for ( let key in currentfFilterItems ) {
				let filter = currentfFilterItems[key];
				if ( filter['url_key'] === urlKey ) {
					return filter;
				}
			}
		},
		processingFilterItem: function( filterItem, value, label, slug ) {
			let taxonomy        = '';
			let ignoredTaxonomy = [ 'priceslider', 'rating' ];
			let itemType        =  filterItem['item_type'];

			if ( ! ignoredTaxonomy.includes( itemType ) ) {
				let sourceOfOptions = filterItem['source_of_options'];
				if ( 'category' === sourceOfOptions ) {
					taxonomy = 'product_cat';
				} else if ( 'attribute' === sourceOfOptions ) {
					taxonomy = filterItem['item_source_attribute'];
				} else if ( 'taxonomy' === sourceOfOptions ) {
					taxonomy = filterItem['item_source_taxonomy'];
				} else if ( 'tag' === sourceOfOptions ) {
					taxonomy = 'product_tag';
				} else if ( 'stock_status' === sourceOfOptions ) {
					taxonomy = 'stock_status';
				} if ( 'orderby' === sourceOfOptions ) {
					filterAttributes['orderby'] = '';
				}
			}

			let term = {
				'taxonomy':  taxonomy,
				'url_key':   filterItem['url_key'], 
				'value':     value,
				'label':     label,
				'fieldType': itemType,
				'slug':      slug,
			};

			if ( 'priceslider' === itemType ) {
				if ( 'two' === filterItem['price_url_format'] ) {
					term['priceUrlKey'] = {
						'minPrice': filterItem['url_key_min_price'],
						'maxPrice': filterItem['url_key_max_price'],
					}
				}
			}

			let multiSelectItem = [ 'rating' ];
			if ( multiSelectItem.includes( itemType ) ) {
				term['multi_select'] = filterItem['multi_select'];
			}

			if ( 'rating' === itemType ) {
				term['up_text'] = filterItem['up_text'];
			}

			pwfFilterActions.updateActiveFilterItems( term, filterItem );
			
			if ( ! pwfWooFilter.isFilterStartAuto()) {
				pwfFilterActions.updateNotices();
				pwfFilterEvent.setApplyResetButtonStatus();
			}
		},
		checkTermExistInActiveFilterItems: function( filterItem ) {
			for ( let key in activeFilterItems ) {
				if ( key === filterItem['url_key'] ) {
					return true;
				}
			}
			return false;
		},
		updateActiveFilterItems: function( term, filterItem  ) {
			/**
			 * Used to add/remove active filter
			 * @param {*} term 
			 * @param {*} filterItem 
			*/
			// when update filter attributes page make it empty or make it empty
			delete filterAttributes['page'];

			let newTerm = {
				'taxonomy':   term['taxonomy'],
				'fieldType': term['fieldType'],
				'notices': [{
					'id'  : term['value'],
					'slug':  term['slug'],
					'label': term['label'],
				}],
			};
			if ( 'priceslider' === term['fieldType'] || 'date' === term['fieldType'] || 'rangeslider' === term['fieldType'] ) {
				// because priceslider value is array by default
				newTerm['values'] = term['value'];
			} else {
				newTerm['values'] = [ term['value'] ];
			}

			if ( term.hasOwnProperty('priceUrlKey') ) {
				newTerm['priceUrlKey'] = term['priceUrlKey'];
			}

			if ( term.hasOwnProperty('rangeUrlKey') ) {
				newTerm['rangeUrlKey'] = term['rangeUrlKey'];
			}
			

			if ( term.hasOwnProperty('dateUrlKey') ) {
				newTerm['dateUrlKey'] = term['dateUrlKey'];
			}

			// Remove old price slider if exist/set before
			let fieldsHasOneValue = [ 'priceslider', 'rangeslider', 'date', 'search' ];
			if ( 'rating' === filterItem['item_type'] && 'on' === term.up_text ) {
				fieldsHasOneValue.push('rating');
			}

			if ( fieldsHasOneValue.includes( term['fieldType'] ) && pwfFilterActions.checkTermExistInActiveFilterItems( term ) && PWF.isEmptyStr(term['value']) ) {
				delete activeFilterItems[ term['url_key'] ];
			} else if ( ( 'radiolist' === term['fieldType'] || 'dropdownlist' === term['fieldType'] ) && 'showall' === term['value']  ) {
				// for showall only for dropdown and radio
				if ( pwfFilterActions.checkTermExistInActiveFilterItems( term ) ) {
					delete activeFilterItems[ term['url_key'] ];
				}
				if ( 'orderby' === term['url_key'] && 'orderby' === filterItem['source_of_options'] ) {
					pwfFilterEvent.checkFilterItemsHasSortingItem('default');
				}			
			} else if ( Object.entries(activeFilterItems).length === 0 ) {
				// check if empty active filters
				activeFilterItems[ term['url_key'] ] = newTerm;
			} else {
				// check if url_key exists
				if ( pwfFilterActions.checkTermExistInActiveFilterItems( term ) && 'search' !== term['fieldType'] ) {
					// check if value exist remove it or add it
					if ( activeFilterItems[ term['url_key'] ].values.includes( term['value'] ) ) {
						// remove this values from url-key.values
						for ( let i = 0; i < activeFilterItems[ term['url_key'] ].values.length; i++ ) {
							if ( activeFilterItems[ term['url_key'] ].values[i] === term['value'] ) { 
								activeFilterItems[ term['url_key'] ].values.splice( i, 1 );
							}
						}
						// remove from notic array
						for ( let i = 0; i < activeFilterItems[ term['url_key'] ].notices.length; i++ ) {
							if ( activeFilterItems[ term['url_key'] ].notices[i]['id'] === term['value'] ) { 
								activeFilterItems[ term['url_key'] ].notices.splice( i, 1 );
							}
						}
						// remove this filter url-key if empty
						if ( Array.isArray( activeFilterItems[ term['url_key'] ].values ) && ! activeFilterItems[ term['url_key'] ].values.length ) {
							delete activeFilterItems[ term['url_key'] ];
						}

						if ( 'orderby' === filterItem['source_of_options'] ) {
							delete activeFilterItems[ term['url_key'] ];
							if ( 'orderby' === term['url_key'] && 'orderby' === filterItem['source_of_options'] ) {
								pwfFilterEvent.checkFilterItemsHasSortingItem('default');
							}
						}

					} else {
						// add this value to url-key
						let emptyItem = [ 'radiolist', 'priceslider', 'date', 'search', 'rangeslider' ];
						if ( 'rating' === filterItem['item_type'] && 'on' === term.up_text ) {
							emptyItem.push('rating');
						}

						if ( 'dropdownlist' === filterItem['item_type'] ) {
							if ( 'orderby' === filterItem['source_of_options'] || 'stock_status' === filterItem['source_of_options'] ) {
								emptyItem.push('dropdownlist');
							} else if ( !  filterItem.hasOwnProperty('multi_select') || ( filterItem.hasOwnProperty('multi_select') && 'on' !== filterItem.multi_select ) ) {
								emptyItem.push('dropdownlist')
							}
						}

						if ( emptyItem.includes( term['fieldType'] ) ) {
							// only active one item filter from this item
							activeFilterItems[ term['url_key'] ].values  = []; // empty values
							activeFilterItems[ term['url_key'] ].notices = []; // empty notices
						}

						let multiselectFields = [ 'colorlist', 'boxlist', 'textlist' ];
						if ( multiselectFields.includes( term['fieldType'] ) && term.hasOwnProperty('multi_select') && 'on' !== term.multi_select ) {
							// only active one item filter from this item
							activeFilterItems[ term['url_key'] ].values  = [];
							activeFilterItems[ term['url_key'] ].notices = [];
						}

						if ( 'priceslider' === term['fieldType'] || 'date' === term['fieldType'] || 'rangeslider' === term['fieldType'] ) {
							// because value here is array
							activeFilterItems[ term['url_key'] ].values = term['value'];
						} else {
							activeFilterItems[ term['url_key'] ].values.push( term['value'] );
						}

						// add this value to notic array
						let notice = {
							'id':  term['value'],
							'label': term['label'],
							'slug':  term['slug'],
						}
						activeFilterItems[ term['url_key'] ].notices.push( notice );
					}
				} else {
					// if term url_key not exist add it
					activeFilterItems[ term['url_key'] ] = newTerm;
				}
			}
		},
		setLabels: function() {
			/**
			 * Fix labels when using depends on if item doesn't exist for first time
			 * and client redirect page with filters
			 */
			if ( ! PWF.isEmptyObj(activeFilterItems) ) {
				let currentHtmlFilter = $('.filter-id-'+ pwfFilterID );
				let likeFields        = [ 'rating', 'checkboxlist' ]
				for ( let key in activeFilterItems ) {
					let fieldType = activeFilterItems[key]['fieldType'];
					let notices   = activeFilterItems[key]['notices'];
					if ( likeFields.includes( fieldType ) ) {
						notices.forEach( function( notice, index ) {
							let item  = $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('[data-slug="' + notice['slug'] + '"]');
							let label = '';
							if ( 'colorlist' === fieldType ) {
								label = $(item).attr('data-item-title');
							} else if ( 'textlist' === fieldType ) {
								label = $(item).find('.text-title').first().text();
							} else if ( 'boxlist' === fieldType ) {
								label = $(item).find('.text-title').text();
							} else if ( 'rating' === fieldType ) {
								label = $(item).attr('data-item-value');
							} else if ( 'checkboxlist' === fieldType ) {
								label = $(item).closest('.pwf-checkbox-label').find('.pwf-title-container').first().find('.text-title').text();
							}
							activeFilterItems[key]['notices'][index]['label'] = label;
						});
					} else if ( 'dropdownlist' === fieldType ) {
						notices.forEach( function( notice, index ) {
							let item  = $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('select').find('option[data-slug="' + notice['slug'] + '"]');
							activeFilterItems[key]['notices'][index]['label'] = $(item).attr('data-title');							
						});
					} else if ( 'radiolist' === fieldType ) {
						notices.forEach( function( notice, index ) {
							let item = $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('input[data-slug="' + notice['slug'] + '"]');
							activeFilterItems[key]['notices'][index]['label'] = $(item).closest('.pwf-item-label').find('.pwf-title-container').find('.text-title').text();
						});						
					} else if ( 'priceslider' === fieldType ) {
						activeFilterItems[key]['notices'][0]['label'] = $(currentHtmlFilter).find('[data-item-key="' + key + '"]').find('.pwf-field-item-title').find('.text-title').text();
					}
				}
			}
		},
		updateNotices: function() {
			if ( getLabels ) {
				pwfFilterActions.setLabels();
				getLabels = false;
			}
			$('.pwf-note-list').empty();
			$('.pwf-note-list').removeClass('empty-active-items')
			let html = '';
			if ( ! PWF.isEmptyObj(activeFilterItems) ) {
				for ( let key in activeFilterItems ) {
					let fieldType = activeFilterItems[key]['fieldType'];
					let notices   = activeFilterItems[key]['notices'];
					if ( 'priceslider' === fieldType ) {
						notices = notices[0];
						let label = translatedText.price + ': ' + pwfFilterActions.priceNotices( notices['id'][0], 'pwf-from' );
						label    += '-' + pwfFilterActions.priceNotices( notices['id'][1], 'pwf-to' );
						html     += pwfFilterActions.clearTemplate( key, '', label, ' pwf-range-slider-note' );				
					} else if ( 'rating' === fieldType ) {
						notices.forEach( function( note ) {
							html += pwfFilterActions.clearTemplate( key, note['id'], translatedText.rate + ' ' + note['label'], ' pwf-rate-note' );
						});
					} else {
						notices.forEach( function( note ) {
							html += pwfFilterActions.clearTemplate( key, note['id'], note['label'] );
						});
					}
				}

				$('.pwf-note-list').append( html );
			} else {
				$('.pwf-note-list').addClass('empty-active-items');
			}
		},
		clearTemplate: function( key, id, label, cssClass = '' ) {
			let itemValue = ( ! PWF.isEmptyStr(id) ) ? ' data-item-value="' + id + '"' : '';

			let html = '<span class="pwf-note-item' + cssClass +  '" data-item-key="' + key + '"' + itemValue + '>';
			html    += '<span class="pwf-remove-filter"><span class="pwf-icon-remove"></span>';
			html    += '<span class="note-text">'+ label +'</span></span></span>';

			return html;
		},
		priceNotices: function( price, css ) {
			let html = '';
			let currencyPosition = pwf_woocommerce_filter.currency_pos;
			let spanStart = '<span class="' + css + '">';
			let spanEnd   = '</span>';
			switch( currencyPosition ) {
				case 'left':
					html += pwfWooFilter.getCurrencyTemplate() + spanStart + price + spanEnd;
					break;
				case 'right':
					html += spanStart + price + spanEnd + pwfWooFilter.getCurrencyTemplate();
					break;
				case 'left_space':
					html += pwfWooFilter.getCurrencyTemplate() + '&nbsp;' + spanStart + price + spanEnd;
					break;
				case 'right_space':
					html += spanStart + price + spanEnd + '&nbsp;'+ pwfWooFilter.getCurrencyTemplate();
					break;
			}

			return html;
		},
		removeSelectedValueFromHTMLFilter: function( filterItem, value ) {
			const currentHtmlFilter = $('.filter-id-'+ pwfFilterID );
			let itemType     = filterItem['item_type'];
			let itemSelector = $(currentHtmlFilter).find('[data-item-key="' + filterItem['url_key'] + '"]');

			if ( 'checkboxlist' === itemType || 'radiolist' === itemType ) {
				let item        = $(itemSelector).find('[value="' + value + '"]');
				let cssSelector = '.pwf-checkboxlist-item';
				if ( 'radiolist' === itemType ) {
					cssSelector = '.pwf-radiolist-label';
				}
				$(item).prop( "checked", false );
				$(item).closest(cssSelector).removeClass('checked');
			} else if ( 'dropdownlist' === itemType ) {
				let item = $(itemSelector).find('select').find('option[value="'+ value + '"]');
				item.prop("selected", false);
				if ( $(item).hasClass('pwf-dropdownlist-item-select2') ) {
					$(item).select2("destroy");
					pwfFilterEvent.addSelect2Event(item);
				}
			} else if ( 'priceslider' === itemType ) {
				let rangeSliderContainer = $(itemSelector).find('.pwf-range-slider');
				if( $(rangeSliderContainer).hasClass('noUi-target') ) {
					let rangeSlider = document.getElementById( $(rangeSliderContainer).attr('id') );
					let minValue    = $(rangeSlider).attr('data-min');
					let maxValue    = $(rangeSlider).attr('data-max');
					rangeSlider.noUiSlider.updateOptions( {
						start: [ parseInt(minValue), parseInt(maxValue) ],
					}, false );
				}
			}
		},
	};
	
	var pwfAjaxQuery = {
		getDatabaseQuery: function() {
			let queryArgs       = {};
			let attributes      = {};
			let usecomponents   = pwfFilterSetting.usecomponents;
			let orderbySelector = pwfFilterSetting.sorting_selector;

			if ( ! PWF.isEmptyObj(activeFilterItems) ) {
				for ( let key in activeFilterItems ) {
					let filter = activeFilterItems[key];
					queryArgs[key] = filter['values'];
				}
			}

			if ( resetButtonClicked ) {
				// if reset button is clicked remove orderby attributes from woo dropdwon menu
				resetButtonClicked = false; // return reset to false
				if ( usecomponents.includes('sorting') && ! PWF.isEmptyStr(orderbySelector) && $( orderbySelector ).length ) {
					$( orderbySelector ).find('select').prop('selectedIndex', '');
				}
				
			} else {
				// If use component sort disabled check if browser url has orderby
				if ( ! filterAttributes.hasOwnProperty('orderby') ) {
					
					if ( usecomponents.includes('sorting') && ! PWF.isEmptyStr(orderbySelector) && $( orderbySelector ).length ) {
						let queryString = window.location.search;
						if ( ! PWF.isEmptyStr(queryString) ) {
							let urlParams  = new URLSearchParams(queryString);
							if ( urlParams.has('orderby') && ! PWF.isEmptyStr(urlParams.get('orderby')) ) {
								filterAttributes['orderby'] = urlParams.get('orderby');
							}
						}
					}				
				}
			}

			if ( ! PWF.isEmptyObj(filterAttributes) ) {
				attributes = filterAttributes;
			}
			
			let data = {
				'query_vars': queryArgs,
				'attributes': attributes, // page number, number of products
			};

			return data;
		},
		getProducts: function( action = '', from = '' ) {
			let getProducts = false;

			if ( 'sorting' === from || 'per_page' === from || 'getpagenumber' === from || 'clearall' === from) {
				getProducts = true;
			} else if ( pwfIsResponsiveView ) {
				if ('canvas-submitButton' === from ) {
					$('.pwf-canvas-close-btn').trigger('click');
					getProducts = true;
				} else if ('canvas-resetButton' === from ) { 
					if ( oldActiveFilterItems ) {
						$('.pwf-canvas-close-btn').trigger('click');
						getProducts = true;
					} else {
						getProducts = false;
					}
				} else if ( 'getproducts' === action ) {
					$('.pwf-canvas-close-btn').trigger('click');
					getProducts = true;
				} else if ( PWF.isEmptyStr(action) && pwfWooFilter.isFilterStartAuto() ) {
					$('.pwf-canvas-close-btn').trigger('click');
					getProducts = true;
				}
			} else {
				if ( 'resetButton' === from ) {
					if ( oldActiveFilterItems ) {
						getProducts = true;
					} else {
						getProducts = false;
					}
				} else if ( 'getproducts' === action ) {
					getProducts = true;
				} else if ( PWF.isEmptyStr(action) && pwfWooFilter.isFilterStartAuto() ) {			
					getProducts = true;
				}
			}

			if ( getProducts ) {
				pwfAjaxQuery.doingAjax();
			}
		},
		prepareAjaxData: function() {
			let queryArgs         = pwfAjaxQuery.getDatabaseQuery();
			let productsContainer = pwfFilterSetting.products_container_selector;

			// how to get number of columns set number of columns
			if ( $(productsContainer).is('[class*="columns-"]') ) {
				let classList  = $(productsContainer).attr('class');
				let cssColumns = classList.match(/columns-\d+/i);
				if ( null !== cssColumns ) {
					cssColumns = cssColumns[0];
					let columns    = cssColumns.match(/\d+/).join('');
					if ( null !== columns ) {
						queryArgs.attributes['columns'] = columns;
					}
				}
			}

			let data = {
				'action':           'get_filter_result', // get_products
				'nonce':            pwf_woocommerce_filter.nonce,
				'filter_id':        pwfFilterID,
				'attributes':       queryArgs.attributes,
				'selected_options': queryArgs.query_vars,
			};

			if ( typeof pwffilterVariables !== 'undefined' ) {
				data['page_id']           = pwffilterVariables.page_id;
				data['is_archive']        = pwffilterVariables.is_archive;
				data['page_type']         = pwffilterVariables.page_type;
				data['taxonomy_id']       = pwffilterVariables.taxonomy_id;
				data['taxonomy_name']     = pwffilterVariables.taxonomy_name;
				data['filter_integrated'] = pwffilterVariables.filter_integrated;

				if ( pwffilterVariables.hasOwnProperty('rule_hidden_items') && Array.isArray( pwffilterVariables.rule_hidden_items ) ) {
					data['rule_hidden_items'] = pwffilterVariables.rule_hidden_items;
				}
			}

			if ( getProductsOnly && queryArgs.attributes.hasOwnProperty('page') ) { 
				data['get_products_only'] = 'true';
			}

			let result = {
				'queryArgs': queryArgs,
				'data':      data,
			};

			return result;
		},
		doingAjax: function() {
			let isRedirectAjax = ( 'on' === pwfFilterSetting.use_ajax ) ? false : true;
			let prepareData = pwfAjaxQuery.prepareAjaxData();
			let data        = prepareData.data;
			let queryArgs   = prepareData.queryArgs;

			data = pwfWooHooks.apply_filters( 'pwf_before_send_ajax_data', data, 10 );

			let urlRequest = pwf_woocommerce_filter.ajaxurl;
			let ajaxMethod = 'POST';
			if ( isRedirectAjax ) {
				ajaxMethod = 'GET';
				data       = '';
				urlRequest = pwf_woocommerce_filter.page_url + pwfController.getBrowserUrlQueryString( false, isRedirectAjax );
			}

			let request = $.ajax({
				method: ajaxMethod,
				dataType: 'html',
				url: urlRequest,
				data: data,
				beforeSend: function() {
					pwfAjaxQuery.beforeSendingAjax();
				}
			});

			request.done( function( result ) {
				let data = {};
				let products;
				let filterHtml;
				let resultCountHTML;
				let paginationHTML;
				let nextPage;
				let productsContainer   = pwfFilterSetting.products_container_selector;
				let resultCountSelector = pwfFilterSetting.result_count_selector;
				let paginationSelector  = pwfFilterSetting.pagination_selector;

				if ( isRedirectAjax ) {
					products   = $(result).find( productsContainer ).html();
					filterHtml = $(result).find('.pwf-filter-container .pwf-woo-filter-inner').html();
					resultCountHTML = $('<div>').append( $(result).find(resultCountSelector).clone() ).html();
					paginationHTML  = $('<div>').append( $(result).find(paginationSelector).clone() ).html();

					if ( typeof products === typeof undefined ) {
						products = $('<div>').append( $(result).find('.woocommerce-info').clone() ).html();
					}
					if ( typeof resultCountHTML === typeof undefined ) {
						resultCountHTML = '';
					}
					nextPage = pwfAjaxQuery.getNextPage( paginationHTML );
				} else {
					result = JSON.parse( result );
					data   = result.data;
					if ( ! data.hasOwnProperty('message') ) {
						products        = data.products;
						filterHtml      = data.filter_html;
						resultCountHTML = data.attributes.html_result_count;
						paginationHTML  = data.attributes.pagination;
						nextPage        = data.attributes.next_page;
					}
				}
				
				if ( data.hasOwnProperty('message') ) {
					//console.log( data.message );
				} else {
					// Add CSS class to each product;
					products = pwfAjaxQuery.addClassToProduct( products );

					if ( getProductsOnly ) {
						if ( 'numbers' === pwfPagination.getType() ) {
							$( productsContainer ).empty();
						}
						$( productsContainer ).append( products ).slideDown();
					} else {
						$( productsContainer ).empty();
						$( productsContainer ).append( products ).slideDown();
					}
					
					pwfAjaxQuery.scrollToTop();

					if ( ! getProductsOnly ) {
						let filterContent = $('.filter-id-' + pwfFilterID + ' .pwf-woo-filter-inner');
						$(filterContent).empty();
						$(filterContent).append( filterHtml );

						pwfFilterEvent.checkToggle();
						pwfFilterEvent.select2();
						pwfFilterEvent.noUiSlider();
					}

					pwfController.updateBrowserUrlQueryString();
					pwfFilterActions.updateNotices();
					pwfFilterEvent.checkFilterItemsHasSortingItem();

					if ( PWF.isEmptyObj(queryArgs.query_vars) ) {
						pwfWooFilter.setOldActiveFilterItems('false');
					} else {
						pwfWooFilter.setOldActiveFilterItems('true');
					}
					pwfFilterEvent.setApplyResetButtonStatus();
					pwfFilterEvent.disableApplyButton();

					pwfAjaxQuery.customizeResultCount( resultCountHTML );
					pwfAjaxQuery.customizeHTMLPagination( paginationHTML, nextPage );

					pwfAjaxQuery.fixBlurImageOnSafari();
					$( document.body ).trigger( 'pwf_filter_js_ajax_done', [ { 'filterID': pwfFilterID, 'paginationType' : pwfPagination.getType(), 'queryArgs': queryArgs } ] );
				}
			});
			request.always( function() {
				pwfAjaxQuery.alwaysAjax();
				getProductsOnly = false;
				pwfAjaxQuery.removeClassFromProduct();
			});
			request.fail(function( jqXHR, textStatus ) {
				getProductsOnly = false;
  				console.log( "Request failed: " + textStatus );
			});
		},
		beforeSendingAjax: function() {
			if ( getProductsOnly ) {
				if ( 'numbers' === pwfPagination.getType() ) {
					$('body').prepend( pwfAjaxQuery.getHTMLLoaderTemplate() );
				} else {
					pwfPagination.addLoadingToLoadMoreButton();
					$('.filter-id-'+ pwfFilterID ).prepend( pwfAjaxQuery.getHTMLLoaderTemplate() );
				}
			} else {
				$('body').prepend( pwfAjaxQuery.getHTMLLoaderTemplate() );
			}
			
			$( document.body ).trigger( 'pwf_filter_before_sending_ajax', [ { 'filterID': pwfFilterID, 'paginationType' : pwfPagination.getType() } ] );
		},
		alwaysAjax: function() {
			if ( getProductsOnly ) {
				if ( 'numbers' === pwfPagination.getType() ) {
					$('body').find('.pwf-overlay').remove();
				} else {
					pwfPagination.removeLoadingToLoadMoreButton();
					$('.filter-id-'+ pwfFilterID ).find('.pwf-overlay').remove();
				}
			} else {
				$('body').find('.pwf-overlay').remove();
			}
	
			$( document.body ).trigger( 'pwf_filter_after_ajax_done', [ { 'filterID': pwfFilterID, 'paginationType' : pwfPagination.getType() } ] );
		},
		getHTMLLoaderTemplate: function() {
			let loader     = '<span class="pwf-loader"></span>';
			return '<div class="pwf-overlay pwf-active">' + loader + '</div>';
		},
		customizeResultCount: function( htmlResultCount ) {
			let resultCountSelector = pwfFilterSetting.result_count_selector;

			if ( pwfFilterSetting.usecomponents.includes('results_count') && $(resultCountSelector).length ) {
				if ( 'numbers' !== pwfPagination.getType() ) {
					let firstProduct = $(resultCountSelector).first().text();
					if ( null !== firstProduct ) {
						firstProduct    = firstProduct.match(/\d/);
						if ( null !== firstProduct ) {
							htmlResultCount = htmlResultCount.replace( /\d+/, firstProduct[0] );
						}
					}
				}

				$(resultCountSelector).each( function() {
					if ( '.woocommerce-result-count' === resultCountSelector ) {
						if ( ! PWF.isEmptyStr(htmlResultCount) ) {
							$(this).replaceWith(htmlResultCount);
						} else {
							$(this).empty();
						}
					} else {
						if ( ! PWF.isEmptyStr(htmlResultCount) ) {
							$(this).empty().append(htmlResultCount);
						} else {
							$(this).empty();
						}
					}
				});
				$( document.body ).trigger( 'pwf_filter_js_ajax_done_result_count', [ { 'filterID': pwfFilterID, 'paginationType' : pwfPagination.getType(), 'htmlResultCount': htmlResultCount } ] );
			}
		},
		customizeHTMLPagination: function( htmlPagination, nextPage = '' ) {
			let paginationSelector = pwfFilterSetting.pagination_selector;
			if ( pwfFilterSetting.usecomponents.includes('pagination') && ! PWF.isEmptyStr(paginationSelector) && $(paginationSelector).length > 0 ) {
				if ( 'numbers' === pwfPagination.getType() ) {
					if ( ! PWF.isEmptyStr(htmlPagination) ) {
						$( paginationSelector ).each( function() {
							$(this).replaceWith( htmlPagination );
						});

						let isRedirectAjax = ( 'on' === pwfFilterSetting.use_ajax ) ? false : true;
						if ( ! isRedirectAjax ) {
							$(paginationSelector).find('a').each( function() {
								$(this).attr("href", pwfController.getPageLinkURL( $(this).attr("href") ) );
							});
						}
					} else {
						$( paginationSelector ).empty();
					}
				}
			}
		},
		addClassToProduct: function( products ) {
			// used to add new CSS class when loading new prdouct, useful for load more and infinite scroll with masonry
			products = $( $.parseHTML( products ) );
			products = $('<div class="pwf-loading-wrapper"></div>').append( products );
			return $(products).children().addClass('pwf-new-product-added');
		},
		removeClassFromProduct: function() {
			setTimeout( function() {
				$( pwfFilterSetting.products_container_selector ).find('.pwf-new-product-added').removeClass('pwf-new-product-added');
			}, 3000 );
		},
		scrollToTop: function() {
			let productsContainer = pwfFilterSetting.products_container_selector;
			let scrollTo          = productsContainer;
			let doScroll          = false;
			if ( getProductsOnly ) {
				if ( 'numbers' === pwfPagination.getType() ) {
					doScroll = true;
				}
			} else {
				doScroll = true;
			}
			if ( ! PWF.isEmptyStr(pwfFilterSetting.scroll_to) ) {
				scrollTo = pwfFilterSetting.scroll_to;
			}
		},
		/**
		 * Get next page number after doing redirect ajax
		 * @param {string} paginationHtml 
		 */
		getNextPage: function ( paginationHtml ) {
			let pageLinks   = $(paginationHtml).find('a');
			let nextPage    = '';
			let currentPage = 1;

			if ( filterAttributes.hasOwnProperty('page') && 1 < filterAttributes.page ) {
				currentPage = filterAttributes['page'];
			}

			$(pageLinks).each( function() {
				let pageNum = null;
				if ( 'product-page' === nextPageString ) {
					pageNum = pwfPagination.extractPageNumberFromProductPage( $(this).attr('href') );
				} else {
					pageNum = pwfPagination.extractPageNumberFromURL( $(this).attr('href').split('?')[0] );
				}

				if ( null !== pageNum && pageNum === ( currentPage + 1 )  ) {
					nextPage = pageNum;
				}
			});

			return nextPage;
		},
		fixBlurImageOnSafari: function() {
			if ( navigator.userAgent.indexOf("Safari") != -1 ) {
				let productsContainer = pwfFilterSetting.products_container_selector;
				$(productsContainer + ' .pwf-new-product-added').find('img[srcset]').each((index, img) => {
					img.outerHTML = img.outerHTML;
				});
			}
		}
	};
	
	var HtmlEntities = function() {};
	HtmlEntities.map = {
		"'": "&apos;",
		"<": "&lt;",
		">": "&gt;",
		" ": "&nbsp;",
		"¡": "&iexcl;",
		"¯": "&macr;",
		"»": "&raquo;",
		"Λ": "&Lambda;",
		"Ξ": "&Xi;",		
		"—": "&mdash;",
		"‘": "&lsquo;",
		"’": "&rsquo;",
		"‚": "&sbquo;",
		"“": "&ldquo;",
		"”": "&rdquo;",
		"„": "&bdquo;",
		"•": "&bull;",
		"…": "&hellip;",
		"←": "&larr;",
		"↑": "&uarr;",
		"→": "&rarr;",
		"↓": "&darr;",
		"≡": "&equiv;",
		"⌈": "&lceil;",
		"⌉": "&rceil;",
		"⌊": "&lfloor;",
		"⌋": "&rfloor;",
		"⟨": "&lang;",
		"⟩": "&rang;",
	};
	HtmlEntities.decode = function(string) {
		var entityMap = HtmlEntities.map;
		for (var key in entityMap) {
			var entity = entityMap[key];
			var regex = new RegExp(entity, 'g');
			string = string.replace(regex, key);
		}
		string = string.replace(/&quot;/g, '"');
		string = string.replace(/&amp;/g, '&');
		return string;
	}
	HtmlEntities.encode = function(string) {
		var entityMap = HtmlEntities.map;
		string = string.replace(/&/g, '&amp;');
		string = string.replace(/"/g, '&quot;');
		for (var key in entityMap) {
			var entity = entityMap[key];
			var regex = new RegExp(key, 'g');
			string = string.replace(regex, entity);
		}
		return string;
	}
	
	pwfWooFilter.init();
}(jQuery));