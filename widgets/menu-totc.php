<?php
namespace GlobalAssistant;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Menu_Totc extends Widget_Base
{

    public function get_name()
    {
        return 'totc-menu';
    }

    public function get_title()
    {
        return __('Menu TOTC', 'totc-addons');
    }

    public function get_categories()
    {
        return array('allaroundwidget');
    }

    public function get_icon()
    {
        return 'eicon-allaround-icon';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $menus = wp_get_nav_menus();
        $menu_options = array();
        foreach ($menus as $menu) {
            $menu_options[$menu->term_id] = $menu->name;
        }

        $this->add_control(
            'menu_id',
            [
                'label' => __('Select Menu', 'totc-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $menu_options,
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings();
        $menu_id = $settings['menu_id'];

        if (!empty($menu_id)) {
            $menu_items = wp_get_nav_menu_items($menu_id);

            // Output the HTML structure for the off-canvas menu
            ?>
            <div class="menu-toggle" aria-controls="menu" aria-expanded="false">
                <span class="hamburger"></span>
            </div>
			<div id="off-canvas-menu" class="offcanvas-menu">
				<nav class="menu">
					<div class="menu-close" aria-controls="menu" aria-expanded="false">
					<span class="cross-icon"></span>
				</div>
					<ul class="menu__list">
						<?php foreach ($menu_items as $item) : ?>
							<li class="menu__item"><a href="<?php echo esc_url($item->url); ?>" class="menu__link"><?php echo esc_html($item->title); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			</div>
            <div class="overlay"></div>
			<style>
				.offcanvas-menu {
					position: fixed;
					top: 0px;
					left: -250px;
					height: 100%;
					width: 250px;
					z-index: 2;
					box-shadow: 0 0 5px 1px rgba(0, 0, 0, .75);
					transition-property: transform, left, right;
					transition-delay: 0s, 0s, 0s;
					transition-timing-function: ease-in-out;
					transition-duration: 150ms;
					background-color: rgba(16, 16, 16, .8);
					color: white;
					display: flex;
					align-items: center;
					justify-content: center;
					padding-top: 100px;
					padding-bottom: 100px;
				}


				.offcanvas-menu--opened {
					transform: translate3d(250px, 0px, 0px);
				}

                .overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    opacity: 0;
                    z-index: -1;
                }
				.overlay.overlay--open {
                    position: fixed;
					display: block;
					opacity: 1;
                    z-index: 1;
				}

                .menu-toggle {
					background: transparent;
					border: none;
					cursor: pointer;
					padding: 0;
					display: flex;
					align-items: center;
					width: 35px;
					height: 35px;
                    opacity: 1;
				}

                .menu-toggle.menu-toggle--active {
                    opacity: 0;
                }

                .hamburger {
                    width: 30px;
                    height: 2px;
                    background-color: white;
                    position: relative;
                }

                .hamburger::before,
                .hamburger::after {
                    content: '';
                    width: 30px;
                    height: 2px;
                    background-color: white;
                    position: absolute;
                    left: 0;
                }

                .hamburger::before {
                    top: -8px;
                }

                .hamburger::after {
                    top: 8px;
                }

                .dark_header .hamburger::before,
                .dark_header .hamburger::after,
                .dark_header .hamburger {
                    background-color: #4a4a4a;
                }

                .menu-close {
                    position: absolute;
                    top: 40px;
                    right: 20px;
                    z-index: 3;
					cursor: pointer;
                }

                #off-canvas-menu .menu__list {
                    list-style: none;
                    padding: 0;
                }

                #off-canvas-menu .menu__item {
                    margin-bottom: 10px;
                }

                #off-canvas-menu .menu__item .menu__link {
                    color: white;
                    text-decoration: none;
                }
				.cross-icon {
					position: relative;
					width: 45px;
					height: 45px;
					display: inline-block;
				}

				.cross-icon::before,
				.cross-icon::after {
					content: '';
					position: absolute;
					top: 50%;
					left: 50%;
					width: 70%;
					height: 2px;
					background-color: white;
					transform: translate(-50%, -50%) rotate(45deg);
				}

				.cross-icon::after {
					transform: translate(-50%, -50%) rotate(-45deg);
				}

			</style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                var toggle = document.querySelector('.menu-toggle');
                var close = document.querySelector('.menu-close');
                var menu = document.querySelector('.menu');
                var offCanvasMenu = document.getElementById('off-canvas-menu');
                var overlay = document.querySelector('.overlay');

                toggle.addEventListener('click', function() {
                    offCanvasMenu.classList.add('offcanvas-menu--opened');
                    overlay.classList.add('overlay--open');
                    toggle.classList.add('menu-toggle--active'); // Add active class to toggle button
                });

                close.addEventListener('click', function() {
                    offCanvasMenu.classList.remove('offcanvas-menu--opened');
                    overlay.classList.remove('overlay--open');
                    toggle.classList.remove('menu-toggle--active'); // Remove active class from toggle button
                });

                overlay.addEventListener('click', function() {
                    offCanvasMenu.classList.remove('offcanvas-menu--opened');
                    overlay.classList.remove('overlay--open');
                    toggle.classList.remove('menu-toggle--active'); // Remove active class from toggle button
                });

                document.addEventListener('click', function(event) {
                    if (!offCanvasMenu.contains(event.target) && !toggle.contains(event.target)) {
                        offCanvasMenu.classList.remove('offcanvas-menu--opened');
                        overlay.classList.remove('overlay--open');
                        toggle.classList.remove('menu-toggle--active'); // Remove active class from toggle button
                    }
                });

                document.querySelectorAll('.menu__link[href^="#"]').forEach(function(anchor) {
                    anchor.addEventListener('click', function(event) {
                        event.preventDefault();
                        var targetId = this.getAttribute('href').substring(1); // Remove leading '#'
                        var targetSection = document.getElementById(targetId);
                        if (targetSection) {
                            var offsetTop = targetSection.offsetTop;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        }
                        // Close the off-canvas menu after scrolling completes
                        setTimeout(function() {
                            offCanvasMenu.classList.remove('offcanvas-menu--opened');
                            overlay.classList.remove('overlay--open');
                            toggle.classList.remove('menu-toggle--active'); // Remove active class from toggle button
                        }, 10); // Adjust the timeout as needed to match your smooth scroll duration
                    });
                });
            });
            </script>

            <?php
        } else {
            echo __('Please select a menu from the settings.', 'totc-addons');
        }
    }

    protected function __content_template()
    {
    }

    public function render_plain_content($instance = [])
    {
    }

}
