class Schedule {
    constructor(countDownDate) {
        this.countDownDate = countDownDate;
    }

    // Update the count down every 1 second
    updateCountDown() {
        let now = new Date().getTime();
        let distance = this.countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        return {
            days: days,
            hours: hours,
            minutes: minutes,
            seconds: seconds,
            distance: distance,
            countDownDate: this.countDownDate,
        };
    }
}

export default Schedule;
