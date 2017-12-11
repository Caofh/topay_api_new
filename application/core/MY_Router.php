<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * Date: 16/7/5
 * Time: 下午3:24
 *
 * MY_Router Class
 *
 * Parses URIs and determines routing
 */

class MY_Router extends CI_Router
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  Set the directory name
     *
     * @access        public
     * @param        string
     * @return        void
     */
    public function set_directory($dir, $append = false)
    {
        $this->directory = $dir . '/';
    }

    /**
     * Validates the supplied segments.  Attempts to determine the path to
     * the controller.
     *
     * @access        private
     * @param        array
     * @return        array
     */

    public function _validate_request($segments)
    {
        if (count($segments) == 0)
        {
            return $segments;
        }

        // Does the requested controller exist in the root folder?
        if (file_exists(APPPATH . 'controllers/' . ucfirst($segments[0]) . '.php'))
        {
            return $segments;
        }

        // Is the controller in a sub-folder?
        if (is_dir(APPPATH . 'controllers/' . $segments[0]))
        {
            $temp = [
                'dir' => [],
                'number' => 0,
                'path' => '',
            ];
            $temp['number'] = count($segments) - 1;

            for ($i = 0; $i <= $temp['number']; $i++)
            {
                $temp['path'] .= $segments[$i] . '/';

                if (is_dir(APPPATH . 'controllers/' . $temp['path']))
                {
                    $temp['dir'][] = str_replace(['/', '.'], '', $segments[$i]);
                }
            }

            $this->set_directory(implode('/', $temp['dir']));
            //$segments = array_diff($segments, $temp['dir']);
            $segmentsTemp = array_slice($segments, 0);

            for ($len = count($segmentsTemp) - 1; $len >= 0; $len--)
            {
                if (isset($temp['dir'][$len]) && $segmentsTemp[$len] === $temp['dir'][$len])
                {
                    unset($segments[$len]);
                }
            }

            $segments = array_values($segments);
            unset($temp);

            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory() . ucfirst($segments[0]) . '.php'))
                {
                    if (!empty($this->routes['404_override']))
                    {
                        $x = explode('/', $this->routes['404_override']);

                        $this->set_directory('');
                        $this->set_class($x[0]);
                        $this->set_method(isset($x[1]) ? $x[1] : 'index');

                        return $x;
                    }
                    else
                    {
                        show_404($this->fetch_directory() . $segments[0]);
                    }
                }
            }
            else
            {
                // Is the method being specified in the route?
                if (strpos($this->default_controller, '/') !== false)
                {
                    $x = explode('/', $this->default_controller);

                    $this->set_class($x[0]);
                    $this->set_method($x[1]);
                }
                else
                {
                    $this->set_class($this->default_controller);
                    $this->set_method('index');
                }

                // Does the default controller exist in the sub-folder?
                if (!file_exists(APPPATH . 'controllers/' . $this->fetch_directory()
                    . ucfirst($this->default_controller) . '.php'))
                {
                    $this->directory = '';
                    return [];
                }

            }

            return $segments;
        }


        // If we've gotten this far it means that the URI does not correlate to a valid
        // controller class.  We will now see if there is an override
        if ( ! empty($this->routes['404_override']))
        {
            $x = explode('/', $this->routes['404_override']);

            $this->set_class($x[0]);
            $this->set_method(isset($x[1]) ? $x[1] : 'index');

            return $x;
        }


        // Nothing else to do at this point but show a 404
        show_404($segments[0]);
    }
}
