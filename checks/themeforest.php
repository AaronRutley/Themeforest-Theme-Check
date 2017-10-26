<?php
/**
 * Extends Theme Check with additional Themeforest reviewer specific checks.
 */
class Themeforest implements themecheck {
	protected $error = array();

	function check( $php_files, $css_files, $other_files )
	{
		$ret = true;

		$checks = array(
			'/echo \$/'                                 => __( 'Possible data validation issues found. All dynamic data must be correctly escaped for the context where it is rendered', 'theme-check' ),
			'/[^a-z0-9](?<!_)mail\s?\(/'                => __( 'Mail functions are plugin territory', 'theme-check' ),
			'/[^a-z0-9](?<!_)wp_mail\s?\(/'             => __( 'Mail functions are plugin territory', 'theme-check' ),
			'/[^a-z0-9](?<!_)call_user_func\s?\(/'      => __( 'call_user_func() found. Possible obscured code', 'theme-check' ),
			'/[^a-z0-9](?<!_)(dirname|basename)\s?\(/'  => __( 'Directory path should be get_template_directory() and not dirname( FILE ) or basename( FILE )', 'theme-check' ),
			'/[^a-z0-9](?<!_)user_contactmethods\s?\(/' => __( 'Extending user_contactmethods is plugin territory', 'theme-check' ),
			'/@import/'                                 => __( 'Do not use @import. Instead, use wp_enqueue to load any external stylesheets', 'theme-check' ),
			'/.bypostauthor{}/'                         => __( 'Do not use empty CSS classes to trick theme check', 'theme-check' ),
			'/.sticky{}/'                               => __( 'Do not use empty CSS classes to trick theme check', 'theme-check' ),
			//'/style=/'                                  => __( 'CSS styling should not be hardcoded anywhere in your theme, either inline on a DOM element or in a style tag', 'theme-check' ),
			);

		$grep = '';

		foreach ( $php_files as $php_key => $phpfile )
		{
			foreach ( $checks as $key => $check )
			{
				checkcount();

				if ( preg_match( $key, $phpfile, $matches ) )
				{
					$filename = tc_filename( $php_key );
					$error = ltrim( trim( $matches[0], '(' ) );
					$grep = tc_grep( $error, $php_key );
					$this->error[] = sprintf('<span class="tc-lead tc-warning">'. __( 'WARNING:', 'theme-check' ) . '</span>: ' . __( 'Found %1$s in the file %2$s. %3$s. %4$s', 'theme-check' ), '<strong>' . $error . '</strong>', '<strong>' . $filename . '</strong>', $check, $grep );
					$ret = false;
				}
			}
		}

		foreach ( $css_files as $php_key => $phpfile )
		{
			foreach ( $checks as $key => $check )
			{
				checkcount();

				if ( preg_match( $key, $phpfile, $matches ) )
				{
					$filename = tc_filename( $php_key );
					$error = ltrim( trim( $matches[0], '(' ) );
					$grep = tc_grep( $error, $php_key );
					$this->error[] = sprintf('<span class="tc-lead tc-warning">'. __( 'WARNING:', 'theme-check' ) . '</span>: ' . __( 'Found %1$s in the file %2$s. %3$s. %4$s', 'theme-check' ), '<strong>' . $error . '</strong>', '<strong>' . $filename . '</strong>', $check, $grep );
					$ret = false;
				}
			}
		}

		return $ret;
	}
	function getError() { return $this->error; }
}
$themechecks[] = new Themeforest;