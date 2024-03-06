# Tailscale Installation Guide
Tech Team has chosen to connect their machines through Tailscale. This file will guide you through installing tailscale and joining the organization network.

## Step 1:
To install Tailscale, run the following command in your terminal:

```bash
curl -fsSL https://tailscale.com/install.sh | sh
```

![Step 1: Installation Command](/.readme_resources/tailscale/00.png)

## Step 2:
After installation, you need to log in to Tailscale using the following command:

```bash
sudo tailscale login
```

![Step 2: Tailscale Login Command](/.readme_resources/tailscale/01.png)

## Step 3:
The terminal will prompt you with a link. Open the link either with control + left-click or right-click and select "Open Link."

![Step 3: Open Tailscale Login Link](/.readme_resources/tailscale/02.png)

## Step 4:
This link will redirect you to the Tailscale login page. Click on "Sign in with GitHub."

![Step 4: Sign in with GitHub](/.readme_resources/tailscale/03.png)

## Step 5:
You will be directed to the GitHub authorization page. Scroll to the bottom of the page and click "Authorize Tailscale."

![Step 5: Authorize Tailscale](/.readme_resources/tailscale/04.png)

## Step 6:
This action will take you to the Tailnet selection page. Make sure to select the option named "it490-tech-team.org.github" and click "Connect."

![Step 6: Connect to Tailnet](/.readme_resources/tailscale/05.png)
