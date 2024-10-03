import { useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Icon } from '@wordpress/icons';
import { DynamicTextarea } from '@help-center/components/ai-chat/DynamicTextarea';
import { send } from '@help-center/components/ai-chat/icons';

export const Question = ({ onSubmit }) => {
	const [inputValue, setInputValue] = useState('');
	const formRef = useRef(null);

	const handleInputChange = (e) => {
		setInputValue(e.target.value);
	};

	const handleKeyDown = (e) => {
		if (e.key === 'Enter' && !e.shiftKey) {
			formRef?.current?.requestSubmit();
		}
	};

	return (
		<form onSubmit={onSubmit} ref={formRef} className="">
			<p className="m-0 mb-1 text-lg font-medium opacity-80">
				{__('Hi there!', 'extendify-local')}
			</p>
			<p className="m-0 mb-6 text-2xl font-medium">
				{__('Ask me any questions about WordPress.', 'extendify-local')}
			</p>
			<div className="relative rounded border border-gray-300 bg-white shadow">
				<DynamicTextarea
					value={inputValue}
					className="h-full w-full flex-1 resize-none py-4 pl-3 pr-10 placeholder-gray-600"
					placeholder={__('Ask your WordPress question…', 'extendify-local')}
					onChange={handleInputChange}
					onKeyDown={handleKeyDown}
				/>
				<button
					type="submit"
					className="absolute bottom-3.5 right-2.5 flex h-6 cursor-pointer items-center border-none bg-transparent fill-current text-gray-700 hover:text-gray-900"
					disabled={!inputValue}>
					<Icon icon={send} className="h-4 w-4" />
				</button>
			</div>
		</form>
	);
};
