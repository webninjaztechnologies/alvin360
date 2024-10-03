import { store as blockEditorStore } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { useDispatch } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Icon, edit, trash } from '@wordpress/icons';
import { useSelectedText } from '@draft/hooks/useSelectedText';

export const SelectedText = ({ loading }) => {
	const [text, setText] = useState();
	const { clearSelectedBlock } = useDispatch(blockEditorStore);
	const { selectedText } = useSelectedText();

	useEffect(() => {
		setText(selectedText);
	}, [setText, selectedText]);

	if (!text) return;

	const truncatedText = () => {
		const preformat = text.split(' ');

		if (preformat.length <= 20) return text;

		return `${text.split(' ', 14).join(' ')}... ${text.slice(
			text.lastIndexOf(' ') - 14,
		)}`;
	};

	return (
		<div
			className="mb-4 flex space-x-2 overflow-hidden rounded-sm border-none bg-gray-100 p-3"
			data-test="existing-text-container">
			<div>
				<Icon icon={edit} className="fill-current" />
			</div>
			<div>
				<div
					className="mb-1 hyphens-auto text-pretty text-gray-800"
					dangerouslySetInnerHTML={{
						__html: truncatedText(),
					}}
				/>
				<div className="mt-3 flex w-full justify-end">
					<Button
						size="compact"
						onClick={clearSelectedBlock}
						disabled={loading}
						icon={trash}
						iconPosition="right"
						className="relative cursor-pointer flex-row-reverse rounded bg-gray-300 text-gray-800 hover:bg-gray-400"
						data-test="remove-selection">
						{__('Remove selection', 'extendify-local')}
					</Button>
				</div>
			</div>
		</div>
	);
};
