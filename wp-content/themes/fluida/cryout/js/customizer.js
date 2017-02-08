/**
 * Extra Customizer JS
 *
 * @package Cryout Framework
 */

var _label_max = 'Maximize';
var _label_min = 'Restore';

var innerHTML = '<button class="button cryout-expand-sidebar button-secondary" aria-expanded="true" aria-label="' + _label_max + '" title="' + _label_max + '" href="#">\
        <span class="collapse-sidebar-label">' + _label_max + '</span>\
		<span class="collapse-sidebar-arrow" title="' + _label_max + '"></span>\
</button> ';


jQuery( document ).ready(function( jQuery ) {

	jQuery('#customize-theme-controls .customize-control-description:not(.cryout-nomove)').each(function() {
			jQuery(this).insertAfter(jQuery(this).parent().children('.customize-control-content, select, input:not(input[type=checkbox]), textarea, .buttonset'));
	});

	if (jQuery('#customize-footer-actions .devices').length>0) {
	/* wp 4.5 or newer */
		jQuery('#customize-footer-actions .devices').prepend(innerHTML);
	} else {
		jQuery('#customize-footer-actions').append(innerHTML);
	}

	// wp hide/show customizer button extender
	jQuery('.collapse-sidebar:not(.cryout-expand-sidebar)').on( 'click', function( event ) {
			if ( jQuery('.wp-full-overlay').hasClass('cryout-maximized') ) {
				jQuery('.wp-full-overlay').removeClass( 'cryout-maximized' );
				jQuery('a.cryout-expand-sidebar span.collapse-sidebar-label').html(_label_max);
				jQuery('a.cryout-expand-sidebar').attr('title',_label_max);
			}

	});
	
	// maximize/restore button 
	jQuery('.cryout-expand-sidebar').on( 'click', function( event ) {
			var label = jQuery('.cryout-expand-sidebar span.collapse-sidebar-label');
			var lebutton = jQuery('.cryout-expand-sidebar');
			if (jQuery(label).html() == _label_max) {
					jQuery(label).html(_label_min);
					jQuery(lebutton).attr('title',_label_min);
					jQuery('.wp-full-overlay').removeClass( 'collapsed' ).addClass( 'expanded' ).addClass( 'cryout-maximized' );
			} else {
					jQuery(label).html(_label_max);
					jQuery(lebutton).attr('title',_label_max);
					jQuery('.wp-full-overlay').removeClass( 'collapsed' ).addClass( 'expanded' ).removeClass( 'cryout-maximized' );
			}
			event.preventDefault();
	});

	/*jQuery('#customize-theme-controls li[id*="cryout-"].control-panel .control-section .accordion-section-title, #customize-theme-controls li[id*="cryoutspecial-"].control-panel .control-section .accordion-section-title').unbind('click');
	jQuery('#customize-theme-controls li[id*="cryout-"].control-panel .control-section .accordion-section-title, #customize-theme-controls li[id*="cryoutspecial-"].control-panel .control-section .accordion-section-title').on( 'click', function( event) {
		event.preventDefault();
		jQuery(this).next('.accordion-section-content').slideToggle();
	});*/

});
