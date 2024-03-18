const puppeteer = require('puppeteer');

async function clickButton() {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto('weekly_updates.html'); // Replace with the path to your HTML file

    // Click the button
    await page.evaluate(() => {
        const button = document.getElementById('monthly-updates-container').querySelector('button');
        if (button) {
            button.click();
        } else {
            console.error('Button not found');
        }
    });

    await browser.close();
}

clickButton();
