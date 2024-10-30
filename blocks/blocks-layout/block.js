(function() {

	const { __ } = wp.i18n;
	const { Component, Fragment, createElement } = wp.element;
	const { IconButton, Icon, DropZone, FormFileUpload, PanelBody, RangeControl,
		CheckboxControl, SelectControl, Toolbar, withNotices } = wp.components;
	const { BlockControls, MediaUpload, MediaPlaceholder, InspectorControls, mediaUpload } = wp.editor;
	const { registerBlockType, createBlock } = wp.blocks;

	registerBlockType( 'meowapps/blocks-layout', {
		title: __( 'Blocks Layout', 'blocks-layouts' ),
		icon: 'camera',
		category: 'layout', // (common, formatting, layout, widgets, embed)
		keywords: [ __( 'section' ), __( 'header' ) ],
		attributes: {
			layoutId: {
				type: 'number',
				default: 0
			},
			layoutTitle: {
				type: 'string',
				default: ""
			},
		},
		supports: {
			customClassName: false,
			className: false,
			html: false,
		},

		edit: function( props ) {
			const layoutId = props.attributes.layoutId;
			const layoutTitle = props.attributes.layoutTitle;
			const focus = props.focus;
			let options = mbl_block_params.layouts.map(x => { 
				return { value: x.ID, label: x.post_title } 
			});
			options.unshift({ value: 0, label: "None" } );
			let info = !props.attributes.layoutTitle ? 
				__('No layout! Pick a layout in the Inspector.', 'blocks-layouts') :
				'Using layout "' + layoutTitle + '"';
			let element = null;
			element = createElement(
				'div', { className: 'mbl-block' },
				createElement('div', { className: 'mwt-info' }, 
				createElement('img', { src: mbl_block_params.logo, className: 'mwt-logo' }), info)
			);

			return [ 
				createElement(
					InspectorControls,
					{ key: 'inspector' },
					createElement(
						SelectControl,
						{
							label: __( 'Layout:', 'blocks-layouts' ),
							value: props.attributes.layoutId ? parseInt(props.attributes.layoutId) : 0,
							onChange: function (value) {
								props.setAttributes({ layoutId: value });
								let newName = value > 0 ? mbl_block_params.layouts.filter(x => x.ID == value)[0]['post_title'] : '';
								props.setAttributes({ layoutTitle: newName });
							},
							options: options,
						}
					)
				),
				element
			];
		},

		save: function( props ) {
			const layoutId = props.attributes.layoutId;
			if (layoutId > 0)
				return "[blocks-layout layout_id=" + (layoutId > 0 ? layoutId : '') + "]"
			else
				return "";
		}
		
	});

})();
