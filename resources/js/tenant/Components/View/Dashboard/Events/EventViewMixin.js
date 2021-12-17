export default {
    data() {
        return {
            eventViewModalId: 'event-view-modal',
            eventViewModalActive: false,
            selectedEvent: null
        }
    },
    methods: {
        viewEventDetails(event) {
            this.selectedEvent = event;
            this.eventViewModalActive = true;
            if(typeof this.lowDetailsModalOpacity === 'function') this.lowDetailsModalOpacity()
        },
        closeEventViewModal() {
            this.selectedEvent = null;
            $(`#${this.eventViewModalId}`).modal('hide');
            this.eventViewModalActive = false;
            if(typeof this.highDetailsModalOpacity === 'function') this.highDetailsModalOpacity()
        },
    }
}