( function( $ ) {

	'use strict';

	$( document ).ready( function () {

	    /**
	     * Saving Options With Ajax
	     */
	    $( '.klabs-settings-form' ).submit( function( e ) {

	        // Don't Refresh Page
	        e.preventDefault();

	        // Show Button Effect
	        $.klabs_saving_options_effect();

	        // Show Loading Message
	        $( '#klabs-wait-message' ).show();

	        // Get Data
	        var data = $( this ).serialize();

	        // Saving Data
	        $.post( logy.ajax_url, data, function( response ) {
	            // Show Processing Text While Saving.
	            $.klabs_saving_options_effect( { step : 'end' } );
	            $( '#klabs-wait-message' ).hide();
	            if ( response == 1 ) {
	                // Show Success Message
	                $.ShowPanelMessage( { type: 'success' } );
	            } else if ( response == 0 ) {
	                // Show Error Message
	                $.ShowPanelMessage( {
	                    msg  : logy.try_later,
	                    type : 'error'
	                });
	            } else if ( response != 'refresh' ) {
					// Show Error Message
					$.ShowPanelMessage( {
						msg  : response,
						type : 'error'
					});
	            } else if ( response == 'refresh' ) {
	                // Show Success Message
	                $.ShowPanelMessage( { type: 'success' } );
	                // Refresh Page
	                location.reload();
	            }
	        });
	    });

	    // Hide Form Message After a while.
	    var fade_message = function() {
	        var t;
	        $( '#klabs-action-message' ).fadeOut( 1000 );
	        clearTimeout( t );
	    }

	    /**
	     * Saving Options Button
	     */
	    $.klabs_saving_options_effect = function( options ) {

	        var settings = $.extend({
	            step: 'processing'
	        }, options );

	       if ( settings.step == 'processing' ) {
	            $( '.klabs-save-options' ).fadeOut( 800, function() {
	                // Disable Save Button while saving Options.
	                $( this ).prop( 'disabled', true );
	                // Changing Button Text
	                var text = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>' + logy.processing ;
	                $( this ).html( text ).fadeIn( 1000);
	            });
	        } else if ( settings.step == 'end' ) {
	            // Processing Saving
	            $( '.klabs-save-options' ).fadeOut( 200, function() {
	                // Changing Button Text
	                $( this ).html( logy.save_changes ).fadeIn( 1000 );
	                // Enable Save Button Again.
	                $( this ).prop( 'disabled', false );
	            });
	        }
	    }

	    // Check if element is already Exist or not
	     $.klabs_isAlreadyExist = function( options ) {

	        // s = settings.
	        var s = $.extend( {
	            value   : null,
	            selector: null,
	            type    : null,
	            old_title: null
	        }, options );

	        if ( s.value ) {
	            // Change value to lowercase.
	            s.value = s.value.toLowerCase().replace( /[^a-zA-Z0-9]/g, '_' );
	        }
	        // in case user updating ad data allow keeping same name.
	        if ( s.old_title ) {
	            // Change value to lowercase.
	            s.old_title = s.old_title.toLowerCase().replace( /[^a-zA-Z0-9]/g, '_' );
	            if ( s.old_title == s.value ) return false;
	        }

	        // Search for value in document.
	        if ( s.type == 'text' ) {
	            var array = s.selector.map( function() {
	                return $( this ).text().toLowerCase().replace( /[^a-zA-Z0-9]/g, '_' );
	            } ).get();
	        } else if ( s.type == 'value' ) {
	            var array = s.selector.map( function() {
	                return $( this ).val().toLowerCase().replace( /[^a-zA-Z0-9]/g, '_' );
	            } ).get();
	        }

	        return array.indexOf( s.value ) > -1;
	    }

	    /**
	     * Panel Message.
	     */
	    $.ShowPanelMessage = function( options ) {
	        var o = $.extend( { type: 'error' }, options ), container, msg, t;
	        if ( o.type == 'error' ) {
	            $( '.klabs-error-popup' ).find( '.klabs-msg-content' ).empty().append( '<p>' + o.msg + '</p>' );
	            $( '.klabs-error-popup' ).addClass( 'is-visible' );
	        } else if ( o.type == 'success' ) {
	            $( '#klabs-action-message' ).html(
	                '<div class="klabs_msg success_msg">' +
	                    '<div class="klabs-msg-icon">' +
	                        '<i class="fa fa-check" aria-hidden="true"></i>' +
	                    '</div>' +
	                    '<span>' + logy.success_msg + '</span>' +
	                '</div>'
	            ).show();
	            t = setTimeout( fade_message, 200 );
	        }
	    }

	    /**
	     * Popup.
	     */

	    // Close Popup
	    $( '.klabs-popup' ).on( 'click', function( e ) {
	        if ( $( e.target ).is( '.klabs-popup-close' ) || $( e.target ).is( '.klabs-popup' ) ) {
	            e.preventDefault();
	            $( this ).removeClass( 'is-visible' );
	        }
	    });

	    // Close Popup if you user Clicked Cancel
	    $( '.klabs-close-popup' ).on( 'click', function( e ) {
	        e.preventDefault();
	        $( '.klabs-popup' ).removeClass( 'is-visible' );
	    });

	    // Close Popup When Clicking The ESC Keyboard Button
	    $( document ).keyup( function( e ) {
	        if ( e.which == '27' ) {
	            $( '.klabs-popup' ).removeClass( 'is-visible' );
	        }
	    });

	    /**
	     * Init Responsive Menus.
	     */
	    $.klabs_ResponsiveFunctions = function() {
	        // Hide Account Menus if width < 768.
	        if ( $( window ).width() < 769 ) {
	            $( '.account-menus ul' ).fadeOut();
	            $( '.klabs-menu-head i' ).attr( 'class', 'fa fa-caret-down' );
	        } else {
	            $( '.account-menus ul' ).fadeIn();
	            $( '.klabs-menu-head i' ).attr( 'class', 'fa fa-caret-up' );
	        }
	    }
	    
	    $.klabs_ResponsiveFunctions();
        
        // ColorPicker
	    if ( $.isFunction( $.fn.wpColorPicker ) ){
	        $( '.klabs-picker-input' ).wpColorPicker();	    
		}

	    /**
	     * # Uploader.
	     */
	    $( document ).on( 'click', '.klabs-upload-button' , function( e ) {

	        e.preventDefault();

	        var kainelabs_uploader,
	            uploader = $( this ).closest( '.klabs-uploader' );

	        kainelabs_uploader = wp.media.frames.kainelabs_uploader = wp.media( {
	            title 	: 'Insert Images',
	            library : { type: 'image' },
	            button  : { text: 'Select' },
	            multiple: false
	        });

	        kainelabs_uploader.on( 'select', function() {
	            var selection = kainelabs_uploader.state().get( 'selection' );
	            selection.map( function( attachment ) {
	                attachment = attachment.toJSON();
	                uploader.find( '.klabs-photo-url' ).val( attachment.url );
	                uploader.find( '.klabs-photo-preview' ).css( 'backgroundImage', 'url(' + attachment.url + ')' );
	            });
	        });

	        kainelabs_uploader.open();

	    });

	    /**
	     * Live Photo Preview
	     */
	    $.enable_live_preview = function() {

	        $( '.klabs-photo-url' ).bind( 'input change', function() {

	            // Get Data.
	            var img_url  = $( this ).val(),
	                uploader = $( this ).closest( '.klabs-uploader' );

	            // If image url not working show default image
	            if ( ! $.klabs_isImgExist( img_url ) ) {
	                img_url = logy.default_img;
	            }

	            // Show Live Preview
	            uploader.find( '.klabs-photo-preview' ).css( 'backgroundImage', 'url(' + img_url + ')' );

	        });

	    }

	    // Init Function
	    $.enable_live_preview();

	    /**
	     * Check if image exist.
	     */
	    $.klabs_isImgExist = function( img_src, type ) {
	        // Get Data.
	        var image = new Image();
			var type = typeof type !== 'undefined' ? type : 'photo';
	        image.src = img_src;
	        if ( image.width == 0 ) {
	            if ( type == 'banner' ) {
	                 $.ShowPanelMessage( {
	                    msg  : logy.banner_url,
	                    type : 'error'
	                } );
	            }
	            return false;
	        }
	        return true;
	    }

	    /**
	     * Reset Options With Ajax
	     */
        $( document ).on( 'click', '.klabs-confirm-reset' , function( e ) {

	    	$( '.klabs-popup' ).removeClass( 'is-visible' );

			e.preventDefault();

			var data, 
				reset_action = '&action=logy_reset_settings',
				reset_elt 	 = $( this ).data( 'reset' ),
				reset_type 	 = '&reset_type=' + reset_elt;

		    // Get Data.
	        if ( reset_elt === 'tab' ) {
	        	var form_data = $( '.klabs-settings-form' ).serialize();
		        data = form_data + reset_action + reset_type;
	        } else if ( reset_elt === 'all' ) {
	        	data = reset_action + reset_type;
	        }

	        // Show Loading Message
	        $( '#klabs-wait-message' ).show();

			$.post( logy.ajax_url, data, function( response ) {
				$( '#klabs-wait-message' ).hide();
				if ( response == 1 ) {
					// Show Success Message
                	$.ShowPanelMessage( { type: 'success' } );
	                // Refresh Page
	                location.reload();
				} else {
					// Show Error Message
					$.ShowPanelMessage( {
						msg  : logy.reset_error,
						type : 'error'
					});
				}
			});
        } );

		/**
		 * Panel Messages
		 */

		// Show/Hide Message
		$( '.klabs-toggle-msg' ).click( function( e ) {
	        e.preventDefault();
	        // Get Parent Box.
			var msg_box = $( this ).closest( '.klabs-panel-msg' ),
				data_id = $( this ).closest( '.klabs-panel-msg' ).data( 'id' ),
				field_item = $( 'input[name="' + data_id + '"]');

			// Display or Hide Box.
	        msg_box.find( '.klabs-msg-content' ).slideToggle( 400, function() {
				// Toggle Box Message.
				msg_box.toggleClass( 'klabs-show-msg' );
				// Change Box Input Value.
				if ( msg_box.hasClass( 'klabs-show-msg' ) ) {
					field_item.val( 'on' );
				} else {
					field_item.val( 'off' );
				}
	        });
		});

		// Remove Panel Message.
		$( '.klabs-close-msg' ).click( function( e ) {
	        // Get Parent Box.
			var msg_box = $( this ).closest( '.klabs-panel-msg' ),
				data_id = $( this ).closest( '.klabs-panel-msg' ).data( 'id' ),
				field_item = $( 'input[name="' + data_id + '"]');
			// Change Box Input Value.
			field_item.val( 'never' );
			// Remove Box.
	        $( this ).closest( '.klabs-panel-msg' ).fadeOut( 600 );
		});

		/**
		 * Responsive Navbar Menu
		 */
		var kl_panel_tabs = $( '.klabs-panel-menu' );

		$( '.kl-toggle-btn' ).change( function( e ) {
				$.initResponsivePanel();
		});

		$.initResponsivePanel = function () {
			if ( $( '.kl-toggle-btn' ).is( ':checked' ) ) {
				kl_panel_tabs.slideDown();
			} else {
		    	kl_panel_tabs.slideUp();
			}
		}

		$( window ).on( 'resize', function ( e ) {
			e.preventDefault();
	        if ( $( window ).width() > 768 ) {
	        	kl_panel_tabs.fadeIn( 1000 );
	        } else {
	        	$.initResponsivePanel();
	        }
		});

		// Hide Panel Menu if user choosed a tab.
		$( '.klabs-sidebar a' ).click( function( e ) {
			if ( $( '.kl-toggle-btn' ).is( ':checked' ) && $( window ).width() < 769 ) {
		        // Change Menu Icon.
				$( '.kl-toggle-btn' ).attr( 'checked', false );
			}
		});

		// Open Reset Tab Settings PopUp
		$( '.klabs-reset-options' ).on( 'click', function( e ) {
			e.preventDefault();
			$( '#klabs_popup_reset_tab' ).addClass( 'is-visible' );
		});

		// Open Reset All Settings PopUp.
		$( '#klabs-reset-all-settings' ).on( 'click', function( e ) {
			e.preventDefault();
			$( '#klabs_popup_reset_all' ).addClass( 'is-visible' );
		});

		/**
		 * # Live Scheme Preview
		 */
		$( document ).on( 'click', '.klabs-panel-scheme .imgSelect label' , function( e ) {
			var panel_scheme = $( this ).prev().val();
			$( '#klabs-panel' ).removeClass().addClass( 'klabs-panel' ).addClass( panel_scheme );
		});

	});

})( jQuery );