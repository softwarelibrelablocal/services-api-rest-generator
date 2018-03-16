<?php

namespace MGDBQ2JSON\ListTable;

use MGDBQ2JSON\Services\DBServices;
use MGDBQ2JSON\Services\Users;

class Logs extends \WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {

        parent::__construct(array(
            'singular' => 'log', //Singular label
            'plural' => 'logs', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));

    }



    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {

        return $columns = array(
            'id' => __('ID', 'mgdbq2json'),
            'date_created' => __('Fecha Creación', 'mgdbq2json'),
            'user' => __('Usuario', 'mgdbq2json'),
            'ip' => __('IP', 'mgdbq2json'),
            'service' => __('Servicio', 'mgdbq2json'),
            'error' => __('Cód. de Error','mgdbq2json'),
            'parameters' => __('Parámetros','mgdbq2json'),
            'response' => __('Respuesta', 'mgdbq2json')
        );

    }




    /**
     * Define the value for each column
     * @param object $item
     * @param string $column_name
     * @return mixed
     */
    function column_default( $item, $column_name ) {

        switch( $column_name ) {
            case 'id':
                echo $item->id;
                break;
            case 'date_created':
                $fecha = date_create_from_format('Y-m-d H:i:s', $item->date_created);
                echo $fecha->format('d/m/Y H:i:s');
                break;
            case 'user':
                $user_info = get_user_by('id', $item->user_id);
                if($user_info){
                    $user_meta = get_user_meta($item->user_id);
                    echo $item->apikey . "<br><a href='" . get_edit_user_link($item->user_id) . "'>" . $user_meta["first_name"][0] . " " . $user_meta["last_name"][0] . "</a><br>" . $user_info->data->user_email;
                }
                else{
                    echo $item->apikey;
                }
                break;
            case 'ip':
                echo $item->IP;
                break;
            case 'service':
                if( $item->service_id != 0 ){
                    $service = DBServices::getInstance()->get_dbservice_by_id($item->service_id);
                    echo $service->get_title();
                }
                break;
            case 'error':
                echo $item->error_code;
                break;
            case 'parameters':
                echo $item->parameters;
                break;
            case 'response':
                    if(empty($item->error_code)): ?>
                        <a href="<?php echo $item->parameters . "&action=view"; ?>" target="_blank"><?php esc_html_e('Ver', 'mgdbq2json'); ?></a> |
                        <a href="<?php echo $item->parameters . "&action=download"; ?>" target="_blank"><?php esc_html_e('Descargar', 'mgdbq2json'); ?></a>
                    <?php endif;
                break;
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }

    }





    /**
     * Definition of sortable columns
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'user'          => array('user_id', true),
        );
        return $sortable_columns;
    }




    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     *
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {

        global $wpdb; //This is used only if making any database queries


        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 10;


        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();


        /**
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable, 'payment_date');





        /* -- Preparing your query -- */
        $query = "SELECT      *
                      FROM        " . $wpdb->prefix . "logs";







        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? esc_sql($_GET["orderby"]) : 'ID';
        $order = !empty($_GET["order"]) ? esc_sql($_GET["order"]) : 'DESC';
        if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }





        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows





        //Which page is this?
        $paged = !empty($_GET["paged"]) ? esc_sql($_GET["paged"]) : '';

        //Page Number
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }


        //How many pages do we have in total?
        $totalpages = ceil($totalitems/$per_page);


        //adjust the query to take pagination into account
        if(!empty($paged) && !empty($per_page)){
            $offset=($paged-1)*$per_page;
            $query.=' LIMIT '.(int)$offset.','.(int)$per_page;
        }



        /* -- Register the pagination -- */
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $per_page,
        ) );




        /* -- Register the Columns -- */
        /*$columns = $this->get_columns();
        $_wp_column_headers[$screen->id]=$columns;*/



        /* -- Fetch the items -- */
        $this->items = $wpdb->get_results($query);
    }


}
