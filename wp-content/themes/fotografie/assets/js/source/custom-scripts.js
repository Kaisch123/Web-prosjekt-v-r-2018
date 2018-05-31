/* global fotografieScreenReaderText */
jQuery(document).ready(function($) {

 /*------------------------------------------------
                MENU AND SEARCH
------------------------------------------------*/


    $('.menu-toggle').click(function(){
        $('.main-navigation ul.nav-menu').slideToggle();
    });

    $( '.search-toggle' ).click( function() {
        $( this ).toggleClass( 'open' );
        $( this ).attr( 'aria-expanded', $( this ).attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
        $( '.search-wrapper' ).toggle();
    });

    var body, masthead, menuToggle, siteNavigation, siteHeaderMenu;

    function initMainNavigation( container ) {
        // Add dropdown toggle that displays child menu items.
        var dropdownToggle = $( '<button />', {
            'class': 'dropdown-toggle',
            'aria-expanded': false
        } ).append( $( '<span />', {
            'class': 'screen-reader-text',
            text: fotografieScreenReaderText.expand
        } ) );

        container.find( '.menu-item-has-children > a' ).after( dropdownToggle );

        // Toggle buttons and submenu items with active children menu items.
        container.find( '.current-menu-ancestor > button' ).addClass( 'toggled-on' );
        container.find( '.current-menu-ancestor > .sub-menu' ).addClass( 'toggled-on' );

        // Add menu items with submenus to aria-haspopup="true".
        container.find( '.menu-item-has-children' ).attr( 'aria-haspopup', 'true' );

        // For default page menu
        container.find( '.page_item_has_children > a' ).after( dropdownToggle );
        container.find( '.current_page_ancestor > button' ).addClass( 'toggled-on' );
        container.find( '.current_page_ancestor > .sub-menu' ).addClass( 'toggled-on' );
        container.find( '.page_item_has_children' ).attr( 'aria-haspopup', 'true' );


        container.find( '.dropdown-toggle' ).click( function( e ) {
            var _this            = $( this ),
                screenReaderSpan = _this.find( '.screen-reader-text' );

            e.preventDefault();
            _this.toggleClass( 'toggled-on' );
            _this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );

            // jscs:disable
            _this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
            // jscs:enable
            screenReaderSpan.text( screenReaderSpan.text() === fotografieScreenReaderText.expand ? fotografieScreenReaderText.collapse : fotografieScreenReaderText.expand );
        } );
    }

    //For Primary Menu
    masthead          = $( '#masthead' );
    menuToggle        = $( '#menu-toggle' );
    siteHeaderMenu    = $( '#site-header-menu' );
    siteNavigation    = $( '#site-navigation' ); // nav
    initMainNavigation( siteNavigation );

    // Enable menuToggle.
    ( function() {
        // Return early if menuToggle is missing.
        if ( ! menuToggle.length ) {
            return;
        }

        // Add an initial values for the attribute.
        menuToggle.add( siteNavigation ).attr( 'aria-expanded', 'false' );

        menuToggle.on( 'click.fotografie', function() {
            $( this ).add( siteHeaderMenu ).toggleClass( 'toggled-on' );

            // jscs:disable
            $( this ).add( siteNavigation ).attr( 'aria-expanded', $( this ).add( siteNavigation ).attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
            // jscs:enable
        } );
    } )();

    // Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
    ( function() {
        if ( ! siteNavigation.length || ! siteNavigation.children().length ) {
            return;
        }

        // Toggle `focus` class to allow submenu access on tablets.
        function toggleFocusClassTouchScreen() {
            if ( window.innerWidth >= 910 ) {
                $( document.body ).on( 'touchstart.fotografie', function( e ) {
                    if ( ! $( e.target ).closest( '.main-navigation li' ).length ) {
                        $( '.main-navigation li' ).removeClass( 'focus' );
                    }
                } );
                siteNavigation.find( '.menu-item-has-children > a' ).on( 'touchstart.fotografie', function( e ) {
                    var el = $( this ).parent( 'li' );

                    if ( ! el.hasClass( 'focus' ) ) {
                        e.preventDefault();
                        el.toggleClass( 'focus' );
                        el.siblings( '.focus' ).removeClass( 'focus' );
                    }
                } );
            } else {
                siteNavigation.find( '.menu-item-has-children > a' ).unbind( 'touchstart.fotografie' );
            }
        }

        if ( 'ontouchstart' in window ) {
            $( window ).on( 'resize.fotografie', toggleFocusClassTouchScreen );
            toggleFocusClassTouchScreen();
        }

        siteNavigation.find( 'a' ).on( 'focus.fotografie blur.fotografie', function() {
            $( this ).parents( '.menu-item' ).toggleClass( 'focus' );
        } );
    })();

    $('.main-navigation button.dropdown-toggle').click(function() {
        $(this).toggleClass('active');
        $(this).parent().find('.children, .sub-menu').first().slideToggle();
    });
    //Primary Menu End



    var loader = $('#loader');
    var loader_container = $('#loader-container');
    var scroll = $(window).scrollTop();
    var scrollup = $('.backtotop');

/*------------------------------------------------
            PRELOADER
------------------------------------------------*/

    loader.fadeOut();
    loader_container.fadeOut();

/*------------------------------------------------
                BACK TO TOP
------------------------------------------------*/

    $( function() {
        $(window).scroll( function () {
            if ( $( this ).scrollTop() > 100 ) {
                $( '#scrollup' ).fadeIn('slow');
                $( '#scrollup' ).show();
            } else {
                $('#scrollup').fadeOut('slow');
                $("#scrollup").hide();
            }
        });

        $( '#scrollup' ).on( 'click', function () {
            $( 'body, html' ).animate({
                scrollTop: 0
            }, 500 );
            return false;
        });
    });

/*------------------------------------------------
                EQUAL HEIGHT
------------------------------------------------*/

    $('.portfolio-wrapper .portfolio-entry-header, .featured-content-wrapper .entry-header').matchHeight();

    $( window ).on( 'load.fotografie resize.fotografie', function () {
        if( $(window).width() > 534 &&  $(window).width() < 1024 ) {
            $('.post-archive .entry-container').matchHeight();
        }
    });


/*------------------------------------------------
    ADD HEADER VIDEO CLASS IF VIDEO IS ENABLED
------------------------------------------------*/
    $( document ).on( 'wp-custom-header-video-loaded', function() {
        $( 'body' ).addClass( 'has-header-video' );
    });

/*------------------------------------------------
                FITVID INITIALIZE
------------------------------------------------*/
    if ( jQuery.isFunction( jQuery.fn.fitVids ) ) {
        jQuery('.hentry, .widget').fitVids();
    }

/*------------------------------------------------
                END JQUERY
------------------------------------------------*/

});
