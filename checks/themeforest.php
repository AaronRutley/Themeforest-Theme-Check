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
			'/@import/'                                           => __( 'Do not use @import. Instead, use wp_enqueue to load any external stylesheets and fonts correctly', 'theme-check' ),
			'/.bypostauthor{}/'                                   => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/.sticky{}/'                                         => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/.gallery-caption{}/'                                => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/.screen-reader-text{}/'                             => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/ .wp-caption-text{}/'                               => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/.bypostauthor {}/'                                   => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/.sticky {}/'                                         => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/.gallery-caption {}/'                                => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/.screen-reader-text {}/'                             => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/ .wp-caption-text {}/'                               => __( 'Do not use empty CSS classes to try to trick theme check', 'theme-check' ),
			'/key=/'                                              => __( 'Possible personal API key found', 'theme-check' ),
			'/@\$/'                                               => __( 'Possible error suppression is being used', 'theme-check' ),
			'/@include/'                                          => __( 'Possible error suppression is being used', 'theme-check' ),
			'/@require/'                                          => __( 'Possible error suppression is being used', 'theme-check' ),
			'/@file/'                                             => __( 'Possible error suppression is being used', 'theme-check' ),
			'/[^a-z0-9](?<!_)mail\s?\(/'                          => __( 'Mail functions are plugin territory', 'theme-check' ),
			'/[^a-z0-9](?<!_)wp_mail\s?\(/'                       => __( 'Mail functions are plugin territory', 'theme-check' ),
			'/[^a-z0-9](?<!_)call_user_func\s?\(/'                => __( 'call_user_func() found. Possible obscured code', 'theme-check' ),
			'/[^a-z0-9](?<!_)mkdir\s?\(/'                         => __( 'mkdir() is not allowed. Use wp_mkdir_p() instead', 'theme-check' ),
			'/[^a-z0-9](?<!_)(dirname|basename)\s?\(/'            => __( 'Directory path should be get_template_directory() and not dirname( FILE ) or basename( FILE )', 'theme-check' ),
			'/[^a-z0-9](?<!_)user_contactmethods\s?\(/'           => __( 'Extending user_contactmethods is plugin territory', 'theme-check' ),
			'/[^a-z0-9](?<!_)balanceTags\s?\(/'                   => __( 'Possible data validation issues found. balanceTags() does not escape data', 'theme-check' ),
			'/echo \$/'                                           => __( 'Possible data validation issues found. All dynamic data must be correctly escaped for the context where it is rendered', 'theme-check' ),
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
					$this->error[] = sprintf('<span class="tc-lead tc-warning">'. __( 'WARNING', 'theme-check' ) . '</span>: ' . __( 'Found %1$s in the file %2$s. %3$s. %4$s', 'theme-check' ), '<strong>' . $error . '</strong>', '<strong>' . $filename . '</strong>', $check, $grep );
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