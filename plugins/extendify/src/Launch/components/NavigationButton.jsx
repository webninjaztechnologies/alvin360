import classnames from 'classnames';

export const NavigationButton = (props) => {
	return (
		<button
			{...props}
			className={classnames(
				'button-focus flex items-center rounded border px-6 py-3 leading-6',
				{
					'cursor-not-allowed opacity-50': props.disabled,
				},
				props.className,
			)}
			type="button">
			{props.children}
		</button>
	);
};
