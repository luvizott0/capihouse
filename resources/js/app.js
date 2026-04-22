import data from '@emoji-mart/data';
import { Picker } from 'emoji-mart';

window.emojiPicker = (config = {}) => ({
	open: false,
	pickerMounted: false,

	toggle() {
		this.open = !this.open;

		if (this.open) {
			this.mountPicker();
		}
	},

	close() {
		this.open = false;
	},

	mountPicker() {
		if (this.pickerMounted) {
			return;
		}

		const container = this.$refs.picker;

		if (!container) {
			return;
		}

		const picker = new Picker({
			data,
			locale: 'pt',
			onEmojiSelect: (selectedEmoji) => {
				const emoji = selectedEmoji?.native;

				if (!emoji || !config.target) {
					return;
				}

				const component = this.resolveLivewireComponent();

				if (!component) {
					return;
				}

				component.set(config.target, emoji);

				if (config.closeOnSelect ?? true) {
					this.close();
				}
			},
		});

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

