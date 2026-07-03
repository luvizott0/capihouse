import data from '@emoji-mart/data';
import { Picker } from 'emoji-mart';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

window.Cropper = Cropper;

window.emojiPicker = (config = {}) => ({
	open: false,
	pickerMounted: false,
	label: config.initialLabel ?? '🙂',

	toggle() {
		this.open = !this.open;

		if (this.open) {
			this.mountPicker();
		}
	},

	close() {
		this.open = false;
		this.pickerMounted = false;
	},

	mountPicker() {
		if (this.pickerMounted) {
			return;
		}

		const isMobile = !window.matchMedia('(min-width: 640px)').matches;
		const container = isMobile ? this.$refs.pickerMobile : this.$refs.picker;

		if (!container) {
			return;
		}

		const onEmojiSelect = (selectedEmoji) => {
			const emoji = selectedEmoji?.native;

			if (!emoji || !config.target) {
				return;
			}

			this.label = emoji;

			const component = this.resolveLivewireComponent();

			if (!component) {
				return;
			}

			component.set(config.target, emoji);

			if (config.closeOnSelect ?? true) {
				this.close();
			}
		};

		const picker = new Picker({ data, locale: 'pt', onEmojiSelect });

		container.innerHTML = '';
		container.appendChild(picker);
		this.pickerMounted = true;
	},

	resolveLivewireComponent() {
		const rootWithWireId = this.$el.closest('[wire\\:id]');
		const wireId = rootWithWireId?.getAttribute('wire:id');

		if (!wireId || !window.Livewire) {
			return null;
		}

		return window.Livewire.find(wireId);
	},
});

