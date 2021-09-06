import SidebarRow from "../common/presentational/SidebarRow";
import { CollectionIcon, PlusCircleIcon } from "@heroicons/react/outline";

import styles from "../../styles/scss/components/shared/Sidebar.module.scss";

function Sidebar() {
  return (
    <aside className="bg-white p-2 h-screen max-w-[300px]">
      <nav className="flex flex-col divide-y">
        <ul className="mt-5">
          <SidebarRow
            Icon={PlusCircleIcon}
            url="/workspace/create"
            title="Add Workspace"
          />
        </ul>
        <ul className="mt-5 divide-y">
          <li>
            <ul className="my-3">
              <SidebarRow
                Icon={CollectionIcon}
                url="/workspace/1"
                title="Workspace 1"
              />
              <SidebarRow url="/project/11" title="Project 1" />
              <SidebarRow url="/project/12" title="Project 2" />
            </ul>
          </li>
          <li>
            <ul className="my-3">
              <SidebarRow
                Icon={CollectionIcon}
                url="/workspace/2"
                title="Workspace 2"
              />
              <SidebarRow url="/project/21" title="Project 1" />
              <SidebarRow url="/project/22" title="Project 2" />
            </ul>
          </li>
        </ul>
      </nav>
    </aside>
  );
}

export default Sidebar;
