import dynamic from "next/dynamic";
import { useState, useEffect } from "react";
import { getProviders, getSession, getCsrfToken } from "next-auth/client";
import { DragDropContext, Droppable } from "react-beautiful-dnd";
import Authenticate from "../../components/layouts/Authenticate";
import Layout from "../../components/layouts/Layout";

const Workflow = dynamic(() =>
  import("../../components/common/smart/workflow/Workflow")
);

function SingleProject({
  session,
  csrfToken,
  providers,
  uuid,
  projectWorkflows,
}) {
  if (!session)
    return <Authenticate csrfToken={csrfToken} providers={providers} />;

  const [windowReady, setWindowReady] = useState(false);
  useEffect(() => {
    setWindowReady(true);
  }, []);

  const [projectState, setProjectState] = useState(projectWorkflows);
  const [workflowIndex, setWorkflowIndex] = useState(null);

  const onDragStart = (start) => {
    const startWorkflowIndex = projectState.workflowOrder.indexOf(
      start.source.droppableId
    );
    setWorkflowIndex(startWorkflowIndex);
  };

  const onDragUpdate = (update) => {};

  const onDragEnd = (end) => {
    setWorkflowIndex(null);

    const { destination, source, draggableId, type } = end;

    if (!destination) {
      return;
    }

    if (
      destination.droppableId == source.draggableId &&
      destination.index === source.index
    ) {
      return;
    }

    if (type === "workflow") {
      const newWorkflowOrder = Array.from(projectState.workflowOrder);
      newWorkflowOrder.splice(source.index, 1);
      newWorkflowOrder.splice(destination.index, 0, draggableId);

      const newProjectState = {
        ...projectState,
        workflowOrder: newWorkflowOrder,
      };

      return setProjectState(newProjectState);
    }

    const startWorkflow = projectState.workflows[source.droppableId];
    const endWorkflow = projectState.workflows[destination.droppableId];

    if (startWorkflow === endWorkflow) {
      const newTaskUuids = Array.from(startWorkflow.taskUuids);
      newTaskUuids.splice(source.index, 1);
      newTaskUuids.splice(destination.index, 0, draggableId);

      const newWorkflow = {
        ...startWorkflow,
        taskUuids: newTaskUuids,
      };

      const newProjectState = {
        ...projectState,
        workflows: {
          ...projectState.workflows,
          [newWorkflow.uuid]: newWorkflow,
        },
      };

      return setProjectState(newProjectState);
    }

    const startTaskUuids = Array.from(startWorkflow.taskUuids);
    startTaskUuids.splice(source.index, 1);

    const newStartWorkflow = {
      ...startWorkflow,
      taskUuids: startTaskUuids,
    };

    const endTaskUuids = Array.from(endWorkflow.taskUuids);
    endTaskUuids.splice(destination.index, 0, draggableId);

    const newEndWorkflow = {
      ...endWorkflow,
      taskUuids: endTaskUuids,
    };

    const newProjectState = {
      ...projectState,
      workflows: {
        ...projectState.workflows,
        [newStartWorkflow.uuid]: newStartWorkflow,
        [newEndWorkflow.uuid]: newEndWorkflow,
      },
    };

    setProjectState(newProjectState);
  };

  return (
    <Layout title={"Project - " + uuid}>
      <DragDropContext
        onDragStart={onDragStart}
        onDragUpdate={onDragUpdate}
        onDragEnd={onDragEnd}
      >
        {windowReady && (
          <Droppable
            droppableId="all-workflows"
            direction="horizontal"
            type="workflow"
          >
            {(provided) => (
              <div
                className="flex"
                {...provided.droppableProps}
                ref={provided.innerRef}
              >
                {projectState.workflowOrder.map((workflowUuid, index) => {
                  const workflow = projectState.workflows[workflowUuid];
                  const tasks = workflow.taskUuids.map(
                    (taskUuid) => projectState.tasks[taskUuid]
                  );

                  const isDropDisabled = index < workflowIndex;

                  return (
                    <Workflow
                      key={workflow.uuid}
                      workflow={workflow}
                      tasks={tasks}
                      index={index}
                      isDropDisabled={isDropDisabled}
                    />
                  );
                })}
                {provided.placeholder}
              </div>
            )}
          </Droppable>
        )}
      </DragDropContext>
    </Layout>
  );
}

export default SingleProject;

export async function getServerSideProps(context) {
  const { params } = context;
  const { uuid } = params;

  return {
    props: {
      session: await getSession(context),
      csrfToken: await getCsrfToken(context),
      providers: await getProviders(),
      uuid,
      projectWorkflows: {
        tasks: {
          "task-1": { uuid: "task-1", content: "Task 1" },
          "task-2": { uuid: "task-2", content: "Task 2" },
          "task-3": { uuid: "task-3", content: "Task 3" },
          "task-4": { uuid: "task-4", content: "Task 4" },
        },
        workflows: {
          "workflow-1": {
            uuid: "workflow-1",
            title: "To do",
            taskUuids: ["task-1", "task-2", "task-3", "task-4"],
          },
          "workflow-2": {
            uuid: "workflow-2",
            title: "In progress",
            taskUuids: [],
          },
          "workflow-3": {
            uuid: "workflow-3",
            title: "Done",
            taskUuids: [],
          },
        },
        workflowOrder: ["workflow-1", "workflow-2", "workflow-3"],
      },
    },
  };
}
