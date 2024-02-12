document.addEventListener("DOMContentLoaded", function() {
	let currentSlide = 0;
	const slides = document.querySelectorAll(".slide");
	const totalSlides = slides.length;

	function showSlide(index) {
		slides.forEach((slide, i) => {
			if (i === index) {
				slide.style.display = "block";
			} else {
				slide.style.display = "none";
			}
		});
	}

	function nextSlide() {
		currentSlide = (currentSlide + 1) % totalSlides;
		showSlide(currentSlide);
	}

	function prevSlide() {
		currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
		showSlide(currentSlide);
	}

	document.getElementById("prev").addEventListener("click", prevSlide);
	document.getElementById("next").addEventListener("click", nextSlide);

	showSlide(currentSlide);
});
