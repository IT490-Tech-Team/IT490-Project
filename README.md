# BookQuest

Welcome to BookQuest, the collaborative repository developed as part of the IT490 course at the New Jersey Institute of Technology. This repository serves as the central hub for the BookQuest project, where students can access project resources, contribute code, and participate in the development process.

If you want to learn more about BookQuest itself, refer to our [proposal](./docs/proposal.md).

## Want to Contribute?

Ready to contribute to BookQuest? Explore our [development guide](/docs/vm-environment.md) for a hassle-free setup. This guide helps you set up a machine that combines all website parts (Frontend, Backend, DMZ) into one, so you can focus on contributing without worrying about details such as the environments and clusters.

### FAQ

Have any questions on how to do something when developing? Make sure to read the [development FAQ](/docs/development-faq.md) where we answer our most common questions. If your question isn't in the FAQ, feel free to join our discord server and ask questions there!

> **NOTE:** since this is a school project, the discord server is limited to official group members of the project.

## Want to replicate the project?

BookQuest's centerpiece is its website, but it's much more than that. Alongside the website, this project has a deployment system where the website is packaged and deployed across three clusters: dev for developers to collaborate, test for QA testers to assess, and prod for public access. On top of that, we use a load balancer to glue these systems together.

If you'd like to set up the entire environment, including the deployment system and deployer, please refer to our [full project setup guide](/docs/full-project-setup.md).

## More Information

Explore the inner workings of BookQuest through our detailed README files:

| Folder                | Description                                                                                                          | Link                                    |
| --------------------- | -------------------------------------------------------------------------------------------------------------------- | --------------------------------------- |
| **Scripts**           | Folder for miscellaneous scripts, e.g. startup, service_manager.                                                     | [README](./scripts/README.md)           |
| **VMs/backend**       | Website's Core, hosting the database, message broker, and most "services".                                           | [README](./VMs/backend/README.md)       |
| **VMs/dmz**           | Middle ground between API and backend.                                                                               | [README](./VMs/dmz/README.md)           |
| **VMs/frontend**      | Contains files to host website frontend, powered by Apache.                                                          | [README](./VMs/frontend/README.md)      |
| **VMs/deployer**      | Manages VM "packages" and installation logic, simplifying project distribution.                                      | [README](./VMs/deployer/README.md)      |
| **VMs/load-balancer** | Glues the frontends of each environment, enabling access to each cluster (dev, test, or prod) from a singular point. | [README](./VMs/load-balancer/README.md) |


