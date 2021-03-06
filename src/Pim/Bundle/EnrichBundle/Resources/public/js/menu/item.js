'use strict';

/**
 * Base extension for menu
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
define(
    [
        'underscore',
        'oro/translator',
        'pim/form',
        'pim/router',
        'routing',
        'pim/template/menu/item',
        'oro/mediator'
    ],
    function (
        _,
        __,
        BaseForm,
        router,
        Routing,
        template,
        mediator
    ) {
        return BaseForm.extend({
            template: _.template(template),
            events: {
                'click .navigation-item': 'redirect'
            },
            active: false,

            /**
             * {@inheritdoc}
             */
            initialize: function (config) {
                this.config = config.config;

                mediator.on('pim_menu:highlight:item', this.highlight, this);
                mediator.on('pim_menu:redirect:item', this.redirect, this);

                BaseForm.prototype.initialize.apply(this, arguments);
            },

            /**
             * On configure, this module triggers an event to register it to tabs.
             *
             * {@inheritdoc}
             */
            configure: function () {
                this.trigger('pim_menu:column:register_navigation_item', {
                    code: this.getRoute(),
                    label: this.getLabel(),
                    position: this.position
                });

                BaseForm.prototype.configure.apply(this, arguments);
            },

            /**
             * {@inheritdoc}
             */
            render: function () {
                this.$el.empty().append(this.template({
                    title: this.getLabel(),
                    url: Routing.generateHash(this.getRoute()),
                    active: this.active
                }));

                this.delegateEvents();

                return BaseForm.prototype.render.apply(this, arguments);
            },

            /**
             * Redirect the user to the config destination
             *
             * @param {Event} event
             */
            redirect: function (event) {
                event.stopPropagation();

                if (!(event.metaKey || event.ctrlKey) &&
                    (!_.has(event, 'extension') || event.extension === this.code)
                ) {
                    router.redirectToRoute(this.getRoute());
                }
            },

            /**
             * Returns the route of the tab.
             *
             * @returns {string|undefined}
             */
            getRoute: function () {
                return this.config.to;
            },

            /**
             * Returns the displayed label of the tab
             *
             * @returns {string}
             */
            getLabel: function () {
                return __(this.config.title);
            },

            /**
             * Highlight or un-highlight item
             *
             * @param {Event}  event
             * @param {string} event.extension The extension code to highlight
             */
            highlight: function (event) {
                this.active = (event.extension === this.code);

                this.render();
            }
        });
    });
