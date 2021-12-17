import {EVENT} from "../../../Config/ApiUrl";
import EventViewMixin from "../../View/Dashboard/Events/EventViewMixin";

export default {
    mixins: [EventViewMixin],
    data() {
        return {
            isCandidateDisqualifyModalActive: false,
            isCandidateMailingModalActive: false,
            isCandidateEventAddEditModalActive: false,
            selectedCandidate: null,
            selectedJobPost: null,
            selectedApplicant: null,
            eventSelectedUrl: '',
        }
    },
    methods: {
        openCandidateDisqualifyModal(applicant) {
            this.selectedCandidate = applicant;
            this.isCandidateDisqualifyModalActive = true;
            this.lowDetailsModalOpacity();
        },
        closeCandidateDisqualifyModal() {
            this.selectedCandidate = null;
            this.isCandidateDisqualifyModalActive = false;
            this.highDetailsModalOpacity();
        },
        openCandidateMailingModal(applicant) {
            this.selectedCandidate = applicant;
            this.isCandidateMailingModalActive = true;
            this.lowDetailsModalOpacity();
        },
        closeCandidateMailingModal() {
            this.selectedCandidate = null;
            this.isCandidateMailingModalActive = false;
            this.highDetailsModalOpacity();
        },
        openEventAddEditModal(jobPost, applicant, editableEvent) {
            this.selectedJobPost = jobPost;
            this.selectedApplicant = applicant;
            if(editableEvent) this.eventSelectedUrl = `${EVENT}/${editableEvent.id}`
            this.isCandidateEventAddEditModalActive = true;
            this.lowDetailsModalOpacity();
        },
        closeEventAddEditModal() {
            this.eventSelectedUrl = '';
            this.selectedJobPost = null;
            this.selectedApplicant = null;
            this.isCandidateEventAddEditModalActive = false;
            this.highDetailsModalOpacity();
        },
        lowDetailsModalOpacity(opacity = '0.5') {
            setTimeout(() => {
                $('#candidate-details-modal').css({opacity});
            });
        },
        highDetailsModalOpacity(opacity = '1') {
            setTimeout(() => {
                $('#candidate-details-modal').css({opacity});
            });
        }
    }
}